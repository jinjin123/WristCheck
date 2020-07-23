<?php


namespace Drupal\wristcheck_basic\Service;


interface BaseServiceInterface
{
  public function queryEntity($type, callable $callback = null);
}
