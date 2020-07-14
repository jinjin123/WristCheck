<?php

namespace Drupal\maintenance200\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use Drupal\Core\Site\MaintenanceModeInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Routing\RouteMatch;


class MaintenanceModeSubscriber implements EventSubscriberInterface {

  /**
   * @inheritDoc
   */
  public function __construct(ConfigFactoryInterface $configFactory, MaintenanceModeInterface $maintenanceMode, AccountInterface $account) {
    $this->config = $configFactory->get('maintenance200.settings');
    $this->maintenanceMode = $maintenanceMode;
    $this->account = $account;
  }

  /**
   * Respond to RESPONSE Kernel event by setting status code if in maintenance.
   *
   */
  public function onKernelResponse(FilterResponseEvent $event) {
    if ($this->config->get('maintenance200_enabled')) {
      $status_code = $this->config->get('maintenance200_status_code');
      $request = $event->getRequest();
      $routeMatch = RouteMatch::createFromRequest($request);
      $response = $event->getResponse();

      if ($this->maintenanceMode->applies($routeMatch) && !$this->maintenanceMode->exempt($this->account)) {
        $response->setStatusCode($status_code);
        $event->setResponse($response);
      }
    }
  }

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['onKernelResponse', 31];
    return $events;
  }

}
