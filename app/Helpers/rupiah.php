<?php

namespace app\Helpers;

class Rupiah
{
  /**
   * Formats a number into Indonesian currency format.
   *
   * @param float $amount The amount to format.
   * @return string The formatted currency string.
   */
  public static function format(float $amount): string
  {
    if (!is_numeric($amount)) {
      return "Invalid amount";
    }

    // Use number_format with proper options for Indonesian format.
    return "Rp" . number_format($amount, 0, ',', '.');
  }
}
