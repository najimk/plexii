<?php

namespace Drupal\commodity_prices\Service;

class CommodityCalculator {

  /**
   * Determine trend.
   */
  public function getTrend(
    float $old_price,
    float $new_price
  ): string {

    return $new_price >= $old_price
      ? 'up'
      : 'down';
  }

  /**
   * Percentage change.
   */
  public function getPercentageChange(
    float $old_price,
    float $new_price
  ): float {

    if ($old_price == 0) {
      return 0;
    }

    return round(
      (($new_price - $old_price) / $old_price) * 100,
      2
    );

  }

}