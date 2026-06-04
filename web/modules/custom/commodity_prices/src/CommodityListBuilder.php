<?php

namespace Drupal\commodity_prices;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

class CommodityListBuilder extends EntityListBuilder {

  public function buildHeader() {

    $header['name'] =
      $this->t('Commodity');

    $header['old_price'] =
      $this->t('Old Price');

    $header['current_price'] =
      $this->t('Current Price');

    $header['trend'] =
      $this->t('Trend');

    $header['status'] =
      $this->t('Status');

    return $header + parent::buildHeader();
  }

  public function buildRow(
    EntityInterface $entity
  ) {

    $old =
      (float) $entity->get('old_price')->value;

    $new =
      (float) $entity->get('current_price')->value;

    $trend =
      $new >= $old
        ? '▲ Up'
        : '▼ Down';

    $row['name'] =
      $entity->label();

    $row['old_price'] =
      number_format($old, 2);

    $row['current_price'] =
      number_format($new, 2);

    $row['trend'] =
      $trend;

    $row['status'] =
      $entity->get('status')->value
      ? $this->t('Enabled')
      : $this->t('Disabled');

    return $row + parent::buildRow($entity);

  }

}