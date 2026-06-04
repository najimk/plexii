<?php

namespace Drupal\commodity_prices\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\user\EntityOwnerTrait;

/**
 * @ContentEntityType(
*   id = "commodity",
*   label = @Translation("Commodity"),
*   label_collection = @Translation("Commodities"),
*   handlers = {
*     "list_builder" =
*       "Drupal\commodity_prices\CommodityListBuilder",
*     "access" =
*       "Drupal\commodity_prices\Access\CommodityAccessControlHandler",
*     "views_data" =
*       "Drupal\views\EntityViewsData",
*     "form" = {
*       "add" =
*         "Drupal\commodity_prices\Form\CommodityForm",
*       "edit" =
*         "Drupal\commodity_prices\Form\CommodityForm",
*       "delete" =
*         "Drupal\commodity_prices\Form\CommodityDeleteForm"
*     }
*   },
*   base_table = "commodity",
*   revision_table = "commodity_revision",
*   admin_permission = "administer commodity prices",
*   entity_keys = {
*     "id" = "id",
*     "revision" = "revision_id",
*     "uuid" = "uuid",
*     "label" = "name",
*     "owner" = "uid",
*     "published" = "status"
*   },
*   links = {
*     "canonical" =
*       "/admin/content/commodities/{commodity}",
*     "add-form" =
*       "/admin/content/commodities/add",
*     "edit-form" =
*       "/admin/content/commodities/{commodity}/edit",
*     "delete-form" =
*       "/admin/content/commodities/{commodity}/delete",
*     "collection" =
*       "/admin/content/commodities"
*   }
* )
 */
class Commodity extends ContentEntityBase implements CommodityInterface {

  use EntityOwnerTrait;
  use EntityChangedTrait;

  public function getName() {
    return $this->get('name')->value;
  }

  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  public function getOldPrice() {
    return $this->get('old_price')->value;
  }

  public function getCurrentPrice() {
    return $this->get('current_price')->value;
  }

  public function getStatus() {
    return $this->get('status')->value;
  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] =
      BaseFieldDefinition::create('string')
        ->setLabel(t('Commodity Name'))
        ->setRequired(TRUE)
        ->setRevisionable(TRUE)
        ->setSettings([
          'max_length' => 255,
        ]);

    $fields['old_price'] =
      BaseFieldDefinition::create('decimal')
        ->setLabel(t('Old Price'))
        ->setRequired(TRUE)
        ->setRevisionable(TRUE)
        ->setSettings([
          'precision' => 20,
          'scale' => 2,
        ]);

    $fields['current_price'] =
      BaseFieldDefinition::create('decimal')
        ->setLabel(t('Current Price'))
        ->setRequired(TRUE)
        ->setRevisionable(TRUE)
        ->setSettings([
          'precision' => 20,
          'scale' => 2,
        ]);

    $fields['status'] =
      BaseFieldDefinition::create('boolean')
        ->setLabel(t('Enabled'))
        ->setDefaultValue(TRUE)
        ->setRevisionable(TRUE);

    $fields['uid'] =
      BaseFieldDefinition::create('entity_reference')
        ->setLabel(t('Author'))
        ->setSetting('target_type', 'user')
        ->setDefaultValueCallback(
          'Drupal\\user\\EntityOwner::getDefaultEntityOwner'
        );

    $fields['created'] =
      BaseFieldDefinition::create('created')
        ->setLabel(t('Created'));

    $fields['changed'] =
      BaseFieldDefinition::create('changed')
        ->setLabel(t('Changed'));

    return $fields;
  }

  public function getTrend(): string {

    return $this->getCurrentPrice() >= $this->getOldPrice()
      ? 'up'
      : 'down';

  }

  public function postSave(\Drupal\Core\Entity\EntityStorageInterface $storage, $update = TRUE) {

  parent::postSave(
    $storage,
    $update
  );

  \Drupal::service('cache_tags.invalidator')
    ->invalidateTags([
      'commodity_list',
    ]);

}
}