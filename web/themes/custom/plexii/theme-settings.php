<?php

declare(strict_types=1);

/**
 * @file
 * Theme settings form for plexii theme.
 */

use Drupal\Core\Form\FormState;

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function plexii_form_system_theme_settings_alter(array &$form, FormState $form_state): void {

  $form['plexii'] = [
    '#type' => 'details',
    '#title' => t('plexii'),
    '#open' => TRUE,
  ];

  $form['plexii']['example'] = [
    '#type' => 'textfield',
    '#title' => t('Example'),
    '#default_value' => theme_get_setting('example'),
  ];

}
