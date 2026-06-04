<?php

namespace Drupal\commodity_prices\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\commodity_prices\Service\CommodityCalculator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Block(
 *   id = "commodity_ticker_block",
 *   admin_label = @Translation("Commodity Ticker")
 * )
 */
class CommodityTickerBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected EntityTypeManagerInterface $entityTypeManager;

  protected CommodityCalculator $calculator;

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entityTypeManager,
    CommodityCalculator $calculator
  ) {

    parent::__construct(
      $configuration,
      $plugin_id,
      $plugin_definition
    );

    $this->entityTypeManager = $entityTypeManager;
    $this->calculator = $calculator;

  }

  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('commodity_prices.calculator')
    );

  }

  public function defaultConfiguration() {

    return [
      'limit' => 20,
      'sort_by' => 'name',
    ];

  }

  public function blockForm(
    $form,
    FormStateInterface $form_state
  ) {

    $form['limit'] = [
      '#type' => 'number',
      '#title' => $this->t('Items to display'),
      '#default_value' => $this->configuration['limit'],
      '#min' => 1,
    ];

    $form['sort_by'] = [
      '#type' => 'select',
      '#title' => $this->t('Sort By'),
      '#options' => [
        'name' => $this->t('Name'),
        'gainers' => $this->t('Highest Gainers'),
        'losers' => $this->t('Highest Losers'),
      ],
      '#default_value' => $this->configuration['sort_by'],
    ];

    return $form;

  }

  public function blockSubmit(
    $form,
    FormStateInterface $form_state
  ) {

    $this->configuration['limit']
      = $form_state->getValue('limit');

    $this->configuration['sort_by']
      = $form_state->getValue('sort_by');

  }

  public function build() {

    $storage =
      $this->entityTypeManager
        ->getStorage('commodity');

    $ids = $storage->getQuery()
      ->condition('status', 1)
      ->accessCheck(TRUE)
      ->execute();

    $entities = $storage->loadMultiple($ids);

    $items = [];

    foreach ($entities as $entity) {

      $old_price =
        (float) $entity->get('old_price')->value;

      $new_price =
        (float) $entity->get('current_price')->value;

      $items[] = [
        'name' => $entity->label(),
        'price' => number_format(
          $new_price,
          2
        ),
        'trend' => $this->calculator
          ->getTrend(
            $old_price,
            $new_price
          ),
        'change' => $this->calculator
          ->getPercentageChange(
            $old_price,
            $new_price
          ),
      ];

    }

    return [
      '#theme' => 'commodity_ticker_block',
      '#items' => $items,
      '#cache' => [
        'tags' => ['commodity_list'],
      ],
      '#attached' => [
        'library' => [
          'commodity_prices/ticker',
        ],
      ],
    ];

  }

}