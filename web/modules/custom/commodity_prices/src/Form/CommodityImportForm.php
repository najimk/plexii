<?php

namespace Drupal\commodity_prices\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CommodityImportForm extends FormBase {

  public function getFormId() {
    return 'commodity_import_form';
  }

  public function buildForm(
    array $form,
    FormStateInterface $form_state
  ) {

    $form['csv_file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('CSV File'),
      '#upload_location' => 'public://commodity-import/',
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import'),
    ];

    return $form;

  }

  public function submitForm(
    array &$form,
    FormStateInterface $form_state
  ) {
    // Implement import service.
  }

}