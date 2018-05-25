<?php

namespace Drupal\bamboo_twig_extensions\TwigExtension;

use Drupal\Core\Template\TwigEnvironment;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides bridge for Text functions and filters.
 *
 * Expose the features of Twig_Extensions_Extension_Date.
 */
class TwigDate extends \Twig_Extension {
  use StringTranslationTrait;

  public static $units = [
    'y' => 'year',
    'm' => 'month',
    'd' => 'day',
    'h' => 'hour',
    'i' => 'minute',
    's' => 'second',
  ];

  /**
   * List of all Twig functions.
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('bamboo_extensions_time_diff', [$this, 'diff'], ['needs_environment' => TRUE]),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig_extensions.twig.date';
  }

  /**
   * Filter for converting dates to a time ago string.
   *
   * @param \Drupal\Core\Template\TwigEnvironment $env
   *   A Twig_Environment instance.
   * @param string|DateTime $date
   *   String or DateTime object to convert.
   * @param string|DateTime $now
   *   String or DateTime object to compare with.
   *   If none given, the current time will be used.
   * @param string $unit
   *   The returned unit. By default, it will use the most efficient unit.
   * @param bool $humanize
   *   The returned value will be human readable.
   *   If none given, the current time will be used.
   *
   * @return string|int
   *   The converted time.
   *   The results as string or integer depend of the $humanize param.
   */
  public function diff(TwigEnvironment $env, $date, $now = NULL, $unit = NULL, $humanize = TRUE) {
    // Convert both dates to DateTime instances.
    $date = twig_date_converter($env, $date);
    $now = twig_date_converter($env, $now);

    // Get the difference between the two DateTime objects.
    $diff = $date->diff($now);

    // Check for each interval if it appears in the $diff object.
    foreach (self::$units as $attribute => $attribute_unit) {
      $count = $diff->$attribute;

      // Force the unit to the ont passed in parameters.
      if ($unit) {
        $attribute_unit = $unit;
      }

      if (0 !== $count) {
        if ($humanize) {
          $id = sprintf('diff.%s.%s %s', $diff->invert ? 'in' : 'ago', $attribute_unit, '@count');
          return $this->formatPlural((int) $count, $id, $id, ['@count' => $count], ['context' => 'Time difference']);
        } else {
          return $diff->invert ? $count : $count * -1;
        }
      }
    }
  }

}
