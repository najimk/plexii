<?php

namespace Drupal\commodity_prices\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

class CommodityForm extends ContentEntityForm {

  public function buildForm(
    array $form,
    FormStateInterface $form_state
  ) {

    $form = parent::buildForm(
      $form,
      $form_state
    );

    return $form;
  }

  public function validateForm(
    array &$form,
    FormStateInterface $form_state
  ) {

    parent::validateForm(
      $form,
      $form_state
    );

    $old_price = $form_state->getValue('old_price')[0]['value'];
    $current_price = $form_state->getValue('current_price')[0]['value'];

    if ($old_price < 0) {
      $form_state->setErrorByName(
        'old_price',
        $this->t('Old price cannot be negative.')
      );
    }

    if ($current_price < 0) {
      $form_state->setErrorByName(
        'current_price',
        $this->t('Current price cannot be negative.')
      );
    }

  }

  public function save(
    array $form,
    FormStateInterface $form_state
  ) {

    $entity = $this->entity;

    $status = $entity->save();

    if ($status === SAVED_NEW) {

      $this->messenger()->addStatus(
        $this->t(
          'Commodity %label created.',
          ['%label' => $entity->label()]
        )
      );

    }
    else {

      $this->messenger()->addStatus(
        $this->t(
          'Commodity %label updated.',
          ['%label' => $entity->label()]
        )
      );

    }

    $form_state->setRedirect(
      'entity.commodity.collection'
    );

  }

}