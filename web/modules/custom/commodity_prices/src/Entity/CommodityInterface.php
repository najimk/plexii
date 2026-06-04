<?php

namespace Drupal\commodity_prices\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

interface CommodityInterface extends ContentEntityInterface, EntityChangedInterface {

  public function getName();

  public function setName($name);

  public function getOldPrice();

  public function getCurrentPrice();

  public function getStatus();

}