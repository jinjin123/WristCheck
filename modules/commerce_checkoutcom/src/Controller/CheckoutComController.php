<?php

namespace Drupal\commerce_checkoutcom\Controller;

use Drupal\commerce_payment\Controller\PaymentCheckoutController;
use Drupal\commerce_payment\Entity\PaymentInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\commerce_checkoutcom\Plugin\Commerce\PaymentGateway\CheckoutComInterface;
use Drupal\Core\Access\AccessException;
use Drupal\facets\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Checkout\CheckoutApi;
use Drupal\commerce_checkoutcom\ErrorHelper;
use Drupal\commerce_payment\Entity\Payment;
use Drupal\commerce_price\Price;
use Drupal\commerce_payment\Entity\PaymentMethod;
use Drupal\commerce_payment\CreditCard;
use Drupal\Core\Access\AccessResult;
use Drupal\commerce_order\Entity\Order;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handle Checkout.com return urls.
 */
class CheckoutComController extends PaymentCheckoutController {

  /**
   * Provides the "return" checkout payment page.
   *
   * Redirects to the next checkout page, completing checkout.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   */
  public function returnPage(Request $request, RouteMatchInterface $route_match) {
    if ($payment_session_id = $request->query->get('cko-session-id')) {
      $order = $route_match->getParameter('commerce_order');
      $step_id = $route_match->getParameter('step');
      $this->validateStepId($step_id, $order);
      $payment_gateway = $order->get('payment_gateway')->entity;
      $payment_gateway_config = $payment_gateway->getPluginConfiguration();
      $payment_gateway_plugin = $payment_gateway->getPlugin();
      if (!$payment_gateway_plugin instanceof CheckoutComInterface) {
        throw new AccessException('The payment gateway for the order does not implement ' . CheckoutComInterface::class);
      }
      $mode = $payment_gateway_config['mode'] == 'live' ? 1 : -1;
      $checkout_api = new CheckoutApi($payment_gateway_config['secret_key'], $mode, $payment_gateway_config['public_key']);
      try {
        $payment_details = $checkout_api->payments()->details($payment_session_id);
        ErrorHelper::handleErrors($payment_details, 'payment');
      }
      catch (\Exception $e) {
        ErrorHelper::handleException($e);
      }
      $existed_payment = \Drupal::entityTypeManager()
        ->getStorage('commerce_payment')
        ->loadByRemoteId($payment_details->id);
      if (empty($existed_payment)) {
        if ($payment_details->status == "Captured") {
          $payment_state = 'completed';
        }
        elseif ($payment_details->status == "Authorized") {
          $payment_state = 'authorization';
        }
        $payment = Payment::create([
          'type' => 'payment_default',
          'payment_gateway' => $payment_gateway->id(),
          'payment_gateway_mode' => $mode == 1 ? 'live' : 'test',
          'order_id' => $order->id(),
          'amount' => new Price(strval($payment_details->amount / 100), $payment_details->currency),
          'remote_id' => $payment_details->id,
          'remote_state' => $payment_details->status,
          'state' => $payment_state,
        ]);
        $payment->save();

        $customer = $order->getCustomer();
        if ($customer && $customer->isAuthenticated()) {
          $payment_method = $order->get('payment_method')->getValue()[0]['target_id'];
          $payment_method = PaymentMethod::load($payment_method);
          $expires = CreditCard::calculateExpirationTimestamp($payment_details->source['expiry_month'], $payment_details->source['expiry_year']);
          $payment_method->setExpiresTime($expires);
          $payment_method->setRemoteId($payment_details->source['id']);
          $payment_method->save();
        }
      }

      $checkout_flow = $order->get('checkout_flow')->entity;
      $checkout_flow_plugin = $checkout_flow->getPlugin();
      $redirect_step_id = $checkout_flow_plugin->getNextStepId($step_id);

      return $checkout_flow_plugin->redirectToStep($redirect_step_id);
    }
    else {
      throw new AccessDeniedHttpException();
    }
  }

  /**
   * Provides the "cancel" checkout payment page.
   *
   * Redirects to the previous checkout page.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   */
  public function cancelPage(Request $request, RouteMatchInterface $route_match) {
    /** @var \Drupal\commerce_order\Entity\OrderInterface $order */
    $order = $route_match->getParameter('commerce_order');
    $step_id = $route_match->getParameter('step');
    $this->validateStepId($step_id, $order);
    /** @var \Drupal\commerce_payment\Entity\PaymentGatewayInterface $payment_gateway */
    $payment_gateway = $order->get('payment_gateway')->entity;
    $payment_gateway_plugin = $payment_gateway->getPlugin();
    if (!$payment_gateway_plugin instanceof CheckoutComInterface) {
      throw new AccessException('The payment gateway for the order does not implement ' . CheckoutComInterface::class);
    }
    /** @var \Drupal\commerce_checkout\Entity\CheckoutFlowInterface $checkout_flow */
    $checkout_flow = $order->get('checkout_flow')->entity;
    $checkout_flow_plugin = $checkout_flow->getPlugin();

    $this->messenger->addError(t('Payment failed at the payment server. Please review your information and try again.'));

    $previous_step_id = $checkout_flow_plugin->getPreviousStepId($step_id);
    $checkout_flow_plugin->redirectToStep($previous_step_id);
  }

  /**
   * Handle the capture wehook.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   */
  public function webhookCapture(Request $request, RouteMatchInterface $route_match) {
    $data = json_decode($request->getContent());
    $existed_payment = \Drupal::entityTypeManager()
      ->getStorage('commerce_payment')
      ->loadByRemoteId($data->data->id);
    if ($existed_payment->getState() != 'completed') {
      $existed_payment->setState('completed');
      $price = new Price(strval($data->data->amount / 100), $data->data->currency);
      $existed_payment->setAmount($price);
      $existed_payment->setState('completed');
      $existed_payment->save();
    }

    return new Response();
  }

  /**
   * Check checkout.com webhooks authentication.
   */
  public function webhookAuthentication() {
    $request = \Drupal::request();
    $header_cko = $request->headers->get('cko-signature');
    if (!empty($header_cko)) {
      $data = $request->getContent();
      $data_object = json_decode($data);
      $order = Order::load($data_object->data->metadata->order_id);
      // has other unknow msg send this route that will happen get null error bug
      if(!empty($order)){
        //      \Drupal::logger('commerce_checkoutcom')->notice('orderexits'. json_encode($order->toArray()));
        $payment_gateway = $order->get('payment_gateway')->entity;
        $secret_key = $payment_gateway->getPluginConfiguration()['secret_key'];
        $hased_data = hash_hmac('sha256', $data, $secret_key);
        if (hash_equals($hased_data, $header_cko)) {
          return AccessResult::allowed();
        }
      }
    }
    return AccessResult::forbidden();
  }

  /**
   *  hook&checkout order status
   */
  public function NotificationStatus(Request $request, RouteMatchInterface $route_match )
  {
    $arr = json_decode($request->getContent());
    \Drupal::logger('commerce_checkoutcom')->notice('content' . json_encode($arr));
    try {
      switch ($arr->type) {
        case "payment_refunded":
          $database = \Drupal::database();
          $database->update('commerce_payment')
            ->fields(["refunded_amount__number"=> $arr->data->amount,"state"=>'refunded'])
            ->condition("order_id",$arr->data->metadata->order_id )
            ->execute();
          break;
        default:
          break;
      }
    }catch (\Exception $e){
      ErrorHelper::handleException($e);
    }
    return new Response();
  }

}
