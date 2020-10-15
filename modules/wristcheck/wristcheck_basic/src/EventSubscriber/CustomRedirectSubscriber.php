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
        $response = new RedirectResponse('/user/login?check_user_information=1');
        $response->send();
        //exit(0);
      }
      else {
        // logged user, check if user has enough information
        if (!wristcheck_basic_check_user_infomation($uid)) {
          $response = new RedirectResponse('/user/'. $uid .'/edit?destination=/sell');
          $response->send();
          //exit(0);
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
