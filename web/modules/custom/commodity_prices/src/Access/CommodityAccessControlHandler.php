<?php

namespace Drupal\commodity_prices\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

class CommodityAccessControlHandler extends EntityAccessControlHandler {

  protected function checkAccess(
    EntityInterface $entity,
    $operation,
    AccountInterface $account
  ) {

    switch ($operation) {

      case 'view':
        return AccessResult::allowedIfHasPermission(
          $account,
          'view commodity entities'
        );

      case 'update':
        return AccessResult::allowedIfHasPermission(
          $account,
          'edit commodity entities'
        );

      case 'delete':
        return AccessResult::allowedIfHasPermission(
          $account,
          'delete commodity entities'
        );
    }

    return AccessResult::neutral();
  }

  protected function checkCreateAccess(
    AccountInterface $account,
    array $context,
    $entity_bundle = NULL
  ) {

    return AccessResult::allowedIfHasPermission(
      $account,
      'create commodity entities'
    );

  }

}