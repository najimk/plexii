<?php

namespace Drupal\commodity_prices\Controller;

use Drupal\Core\Controller\ControllerBase;

class CommodityDashboardController extends ControllerBase {

  public function dashboard() {

    $storage = $this->entityTypeManager()
      ->getStorage('commodity');

    $ids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->execute();

    $commodities = $storage->loadMultiple($ids);

    $enabled = 0;
    $disabled = 0;

    foreach ($commodities as $commodity) {

      if ($commodity->get('status')->value) {
        $enabled++;
      }
      else {
        $disabled++;
      }

    }

    return [
      '#theme' => 'commodity_dashboard',
      '#total' => count($commodities),
      '#enabled' => $enabled,
      '#disabled' => $disabled,
    ];

  }

}