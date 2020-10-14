<?php

namespace Drupal\wristcheck_basic\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\Core\Url;
/**
* Redirect pages.
*/
class CustomRedirectSubscriber implements EventSubscriberInterface {

  /** @var int */
  private $redirectCode = 301;

  /**
  * Redirect pattern based url
  * @param GetResponseEvent $event
  */
  public function customRedirection(GetResponseEvent $event) {

    $request = \Drupal::request();
    $requestUrl = $request->server->get('REQUEST_URI', null);

    if ($requestUrl=='/node/add/wcshw') {
      //$logged_in = \Drupal::currentUser()->isAuthenticated();
      $user = \Drupal::currentUser()->getAccount();
      $uid = $user->id();
      if ($uid == 0) {
        // anonymous user
        $response = new RedirectResponse('/user/login', $this->redirectCode);
        $response->send();
        exit(0);
      }
      else {
        // logged user, check if user has enough information
        if (!wristcheck_basic_check_user_infomation($uid)) {
          $response = new RedirectResponse('/user/register', $this->redirectCode);
          $response->send();
          // need redirect to sell page after fill user information.
          $tempStore = \Drupal::service('user.private_tempstore')->get('wristcheck_basic');
          $tempStore->set('redirect_to_sell', TRUE);
          \Drupal::logger('wristcheck_basic')->notice('User has no enough info, set redirect_to_sell: TRUE');
          exit(0);
        }
      }
    }

  }

  /**
  * Listen to kernel.request events and call customRedirection.
  * {@inheritdoc}
  * @return array Event names to listen to (key) and methods to call (value)
  */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = array('customRedirection');
    return $events;
  }

}
