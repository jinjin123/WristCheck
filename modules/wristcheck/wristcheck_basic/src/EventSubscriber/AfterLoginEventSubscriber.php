<?php

namespace Drupal\wristcheck_basic\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Subscribe to KernelEvents::REQUEST events.
 */
class AfterLoginEventSubscriber implements EventSubscriberInterface {

  /**
   * @var AccountInterface $account
   */
  protected $account;

  /**
   * The config factory.
   *
   * @var ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a AfterLoginEventSubscriber object.
   *
   * @param AccountInterface $account
   *   The account service.
   * @param ConfigFactoryInterface $config_factory
   *   The config factory service.
   */
  public function __construct(AccountInterface $account, ConfigFactoryInterface $config_factory) {
    $this->account = $account;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::RESPONSE => ['redirectAfterLoginEvent']
    ];
  }

  /**
   * This method is called whenever the KernelEvents::RESPONSE event is
   * dispatched.
   *
   * @param FilterResponseEvent $event
   */
  public function redirectAfterLoginEvent(FilterResponseEvent $event) {
//    $request = $event->getRequest();
//
//    if ($request->get('_route') === 'user.login' && $request->isMethod('POST')) {
//      // Check if destination query param is set, do nothing if so.
//      if (!$request->query->has('destination')) {
//        if (isset($_GET['check_user_information'])) {
//          $uid = $this->account->id();
//          $redirect_url = URL::fromUserInput('/user/' . $uid . '/edit')
//            ->toString();
//          $event->setResponse(new RedirectResponse($redirect_url));
//        }
//      }
//    }
  }

}
