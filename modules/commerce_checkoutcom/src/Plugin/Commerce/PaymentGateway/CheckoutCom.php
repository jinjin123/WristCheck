<?php

namespace Drupal\commerce_checkoutcom\Plugin\Commerce\PaymentGateway;

use Drupal\commerce_payment\Plugin\Commerce\PaymentGateway\OnsitePaymentGatewayBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\commerce_payment\Entity\PaymentInterface;
use Drupal\commerce_payment\CreditCard;
use Drupal\commerce_price\Price;
use Drupal\commerce_payment\Entity\PaymentMethodInterface;
use Checkout\CheckoutApi;
use Checkout\Models\Tokens\Card;
use Checkout\Models\Payments\ThreeDs;
use Checkout\Models\Payments\TokenSource;
use Checkout\Models\Payments\Payment;
use Checkout\Models\Payments\Refund;
use Checkout\Models\Payments\IdSource;
use Checkout\Models\Payments\Capture;
use Checkout\Models\Payments\Voids;
use Checkout\Models\Webhooks\Webhook;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\commerce_payment\PaymentTypeManager;
use Drupal\commerce_payment\PaymentMethodTypeManager;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\commerce_checkoutcom\ErrorHelper;
use Checkout\Library\Exceptions\CheckoutHttpException;
use Drupal\commerce\Response\NeedsRedirectException;
use Drupal\Core\Url;

/**
 * Provides the Checkout.com onsite Checkout payment gateway.
 *
 * @CommercePaymentGateway(
 *   id = "checkoutcom",
 *   label = @Translation("Checkout.com"),
 *   display_label = @Translation("Checkout.com"),
 * )
 */
class CheckoutCom extends OnsitePaymentGatewayBase implements CheckoutComInterface
{

  /**
   * The Checkout.com client.
   *
   * @var Checkout\CheckoutApi
   */
  protected $CheckoutApi;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, PaymentTypeManager $payment_type_manager, PaymentMethodTypeManager $payment_method_type_manager, TimeInterface $time)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $payment_type_manager, $payment_method_type_manager, $time);

    $mode = $this->configuration['mode'] == 'live' ? 1 : -1;
    $this->CheckoutApi = new CheckoutApi($this->configuration['secret_key'], $mode, $this->configuration['public_key']);
  }

  /**
   * Sets the API key after the plugin is unserialized.
   */
  public function __wakeup()
  {
    $mode = $this->configuration['mode'] == 'live' ? 1 : -1;
    $this->CheckoutApi = new CheckoutApi($this->configuration['secret_key'], $mode, $this->configuration['public_key']);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration()
  {
    return [
        'public_key' => '',
        'secret_key' => '',
        'webhook_capture_id' => '',
      ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state)
  {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['secret_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Secret key'),
      '#description' => $this->t('This is the secret key from the checkoutcom manager.'),
      '#default_value' => $this->configuration['secret_key'],
      '#required' => TRUE,
    ];

    $form['public_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Public key'),
      '#description' => $this->t('This is the API key from the Checkout.com channel settings page.'),
      '#default_value' => $this->configuration['public_key'],
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateConfigurationForm($form, $form_state);

    if (!$form_state->getErrors()) {
      $values = $form_state->getValue($form['#parents']);
      $expected_livemode = $values['mode'] == 'live' ? 1 : -1;
      try {
        // Validate the secret key and create payment capture webhook.
        $checkoutApi = new CheckoutApi($values['secret_key'], $expected_livemode, $values['public_key']);
        $webhook_response = $checkoutApi->webhooks()->load($this->configuration['webhook_capture_id']);
        foreach ($webhook_response as $key => $value) {
          $t1 = (array)$value;
          //var_export($t1[0]->{'id'});
          if (isset($t1[0]->{'id'})) {
            if ($t1[0]->{'id'} != null) {
              $form_state->setValue('webhook_capture_id', $t1[0]->{'id'});
            }
          }
        }
//        if ($webhook_response instanceof Webhook) {
//          $form_state->setValue('webhook_capture_id', $webhook_response->getId());
//        }
//        else {
//          throw new CheckoutHttpException('Webhook not defined', '404');
//        }
      } catch (CheckoutHttpException $e) {
        /*
                //block to redirect
                if ($e->getCode() == '404') {
                  $host = \Drupal::request()->getSchemeAndHttpHost();
                  $catpure_url = Url::fromRoute('commerce_checkoutcom.webhook.capture');
                  $webhook = new Webhook($host . $catpure_url->toString());
                  $webhook_response = $checkoutApi->webhooks()->register($webhook, ['payment_captured']);
                  $form_state->setValue('webhook_capture_id', $webhook_response->getId());
                }
                else {
                  $form_state->setError($form['secret_key'], $this->t('Invalid credintials.'));
                }
        */
        $form_state->setError($form['secret_key'], $this->t('Invalid credintials.'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitConfigurationForm($form, $form_state);
    $values = $form_state->getValue($form['#parents']);
    $this->configuration['public_key'] = $values['public_key'];
    $this->configuration['secret_key'] = $values['secret_key'];
    $this->configuration['webhook_capture_id'] = $form_state->getValue('webhook_capture_id');
  }

  /**
   * {@inheritdoc}
   */
  public function createPayment(PaymentInterface $payment, $capture = TRUE)
  {
    $this->assertPaymentState($payment, ['new']);
    $payment_method = $payment->getPaymentMethod();
    $order = $payment->getOrder();
    $customer = $order->getCustomer();

    if (substr($payment_method->getRemoteId(), 0, 3) == 'tok') {
      // Create a payment method instance with card details.
      $source = new TokenSource($payment_method->getRemoteId());
    } else {
      $source = new IdSource($payment_method->getRemoteId());
    }
    // Prepare the payment parameters.
    $checkout_payment = new Payment($source, $payment->getAmount()->getCurrencyCode());
    $checkout_payment->capture = $capture;
    $checkout_payment->amount = $payment->getAmount()->getNumber() * 100;
    $checkout_payment->threeDs = new ThreeDs(TRUE);
    $host = \Drupal::request()->getSchemeAndHttpHost();
    $success_url = Url::fromRoute('commerce_checkoutcom.checkout.return', ['commerce_order' => $order->id(), 'step' => 'payment']);
    $failure_url = Url::fromRoute('commerce_checkoutcom.checkout.cancel', ['commerce_order' => $order->id(), 'step' => 'payment']);
    $checkout_payment->success_url = $host . $success_url->toString();
    $checkout_payment->failure_url = $host . $failure_url->toString();
    $checkout_payment->reference = "Order Number: " . $order->id();
    $checkout_payment->metadata = new \stdClass();
    $checkout_payment->metadata->order_id = $order->id();
    $checkout_payment->payment_ip = $order->getIpAddress();
    $checkout_payment->customer = new \stdClass();

    if ($customer && $customer->isAuthenticated()) {
      $checkout_payment->customer->email = $customer->getEmail();
      if ($remote_customer_id = $this->getRemoteCustomerId($customer)) {
        $checkout_payment->customer->id = $remote_customer_id;
      }
    } else {
      $checkout_payment->customer->email = $order->getEmail();
    }

    // Send the request and retrieve the response.
    try {
      $payment_response = $this->CheckoutApi->payments()->request($checkout_payment);
      \Drupal::logger('commerce_checkoutcom')->notice('OrderStatus'. json_encode($payment_response));
      ErrorHelper::handleErrors($payment_response, 'payment');
    } catch (NeedsRedirectException $e) {
      throw $e;
    } catch (\Exception $e) {
      if (in_array('token_expired', json_decode($e->getBody())->error_codes)) {
        ErrorHelper::handleTokenExpiration($e, $payment_method);
      }
      ErrorHelper::handleException($e);
    }

    if ($customer && $customer->isAuthenticated()) {
      $payment_method->setRemoteId($payment_response->source['id']);
      $expires = CreditCard::calculateExpirationTimestamp($payment_response->source['expiry_month'], $payment_response->source['expiry_year']);
      $payment_method->setExpiresTime($expires);
      //$payment_method->save();
    }

    $this->setRemoteCustomerId($customer, $payment_response->customer['id']);

    $payment->setRemoteState($payment_response->status);
    if ($payment_response->status == "Captured") {
      $payment_state = 'completed';
    } elseif ($payment_response->status == "Authorized") {
      $payment_state = 'authorization';
    }
    $payment->setState($payment_state);
    $payment->setRemoteId($payment_response->id);
    $payment->save();
  }

  /**
   * {@inheritdoc}
   */
  public function refundPayment(PaymentInterface $payment, Price $amount = NULL)
  {
    $this->assertPaymentState($payment, ['completed', 'partially_refunded']);
    // If not specified, refund the entire amount.
    $amount = $amount ?: $payment->getAmount();
    $this->assertRefundAmount($payment, $amount);

    // Perform the refund request here, throw an exception if it fails.
    try {
      $refund = new Refund($payment->getRemoteId());
      $refund->amount = $amount->getNumber() * 100;
      $refund_response = $this->CheckoutApi->payments()->refund($refund);
      ErrorHelper::handleErrors($refund_response, 'refund');
    } catch (\Exception $e) {
      ErrorHelper::handleException($e);
    }

    // Determine whether payment has been fully or partially refunded.
    $old_refunded_amount = $payment->getRefundedAmount();
    $new_refunded_amount = $old_refunded_amount->add($amount);
    if ($new_refunded_amount->lessThan($payment->getAmount())) {
      $payment->setState('partially_refunded');
    } else {
      $payment->setState('refunded');
    }

    $payment->setRefundedAmount($new_refunded_amount);
    $payment->save();
  }

  /**
   * {@inheritdoc}
   */
  public function capturePayment(PaymentInterface $payment, Price $amount = NULL)
  {
    $this->assertPaymentState($payment, ['authorization']);
    // If not specified, capture the entire amount.
    $amount = $amount ?: $payment->getAmount();

    // Perform the capture request here, throw an exception if it fails.
    try {
      $remote_id = $payment->getRemoteId();
      $decimal_amount = $amount->getNumber() * 100;
      $capture = new Capture($remote_id);
      $capture->amount = $decimal_amount;
      $capture_response = $this->CheckoutApi->payments()->capture($capture);
      ErrorHelper::handleErrors($capture_response, 'capture');
    } catch (\Exception $e) {
      ErrorHelper::handleException($e);
    }

    $payment->setState('completed');
    $payment->setAmount($amount);
    $payment->save();
  }

  /**
   * {@inheritdoc}
   */
  public function voidPayment(PaymentInterface $payment)
  {
    $this->assertPaymentState($payment, ['authorization']);
    // Perform the void request here, throw an exception if it fails.
    try {
      $remote_id = $payment->getRemoteId();
      $void = new Voids($remote_id);
      $void_response = $this->CheckoutApi->payments()->void($void);
      ErrorHelper::handleErrors($void_response, 'void');
    } catch (\Exception $e) {
      ErrorHelper::handleException($e);
    }

    $payment->setState('authorization_voided');
    $payment->save();
  }

  /**
   * {@inheritdoc}
   */
  public function createPaymentMethod(PaymentMethodInterface $payment_method, array $payment_details)
  {
    $required_keys = [
      'type', 'number', 'expiration', 'security_code',
    ];
    foreach ($required_keys as $required_key) {
      if (empty($payment_details[$required_key])) {
        throw new \InvalidArgumentException(sprintf('$payment_details must contain the %s key.', $required_key));
      }
    }

    $card = new Card($payment_details['number'], $payment_details['expiration']['month'], $payment_details['expiration']['year']);
    $card->cvv = $payment_details['security_code'];

    try {
      $token_response = $this->CheckoutApi->tokens()->request($card);
      ErrorHelper::handleErrors($token_response, 'token');
    } catch (\Exception $e) {
      ErrorHelper::handleException($e);
    }

    $owner = $payment_method->getOwner();
    // If in user add payment method page, verify credit card to save it's
    // source id, because token expired after 15 mins.
    if ($owner && $owner->isAuthenticated() && \Drupal::routeMatch()->getRouteName() == 'entity.commerce_payment_method.add_form') {
      $source = new TokenSource($token_response->token);
      $checkout_payment = new Payment($source, 'USD');
      $checkout_payment->capture = FALSE;
      $checkout_payment->amount = 0;
      $checkout_payment->customer = new \stdClass();
      $checkout_payment->customer->email = $owner->getEmail();
      if ($remote_customer_id = $this->getRemoteCustomerId($owner)) {
        $checkout_payment->customer->id = $remote_customer_id;
      }
      // Send the request and retrieve the response.
      try {
        $verification_response = $this->CheckoutApi->payments()->request($checkout_payment);
        ErrorHelper::handleErrors($verification_response, 'payment');
      } catch (\Exception $e) {
        ErrorHelper::handleException($e);
      }

      $payment_method->setRemoteId($verification_response->getSourceId());
      $expires = CreditCard::calculateExpirationTimestamp($verification_response->source['expiry_month'], $verification_response->source['expiry_year']);
    } else {
      $payment_method->setRemoteId($token_response->token);
      $expires = CreditCard::calculateExpirationTimestamp($token_response->expiry_month, $token_response->expiry_year);
    }

    $payment_method->setExpiresTime($expires);
    $payment_method->card_type = $payment_details['type'];
    $payment_method->card_number = $token_response->last4;
    $payment_method->card_exp_year = $token_response->expiry_year;
    $payment_method->card_exp_month = $token_response->expiry_month;
    //$payment_method->save();
  }

  /**
   * {@inheritdoc}
   */
  public function deletePaymentMethod(PaymentMethodInterface $payment_method)
  {
    $payment_method->delete();
  }

}
