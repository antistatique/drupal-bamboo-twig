<?php

namespace Drupal\bamboo_twig_extensions\TwigExtension;

use Twig\TwigFilter;
use Drupal\Core\Template\TwigEnvironment;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Twig\Extension\AbstractExtension;

/**
 * Provides bridge for Text functions and filters.
 *
 * Expose the features of Twig_Extensions_Extension_Date.
 */
class TwigDate extends AbstractExtension {
  use StringTranslationTrait;

  /**
   * Abbreviated units and the full english PHP analogy.
   *
   * @var string[]
   */
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
      new TwigFilter('bamboo_extensions_time_diff', [$this, 'diff'], ['needs_environment' => TRUE]),
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

    $count = 0;

    // Check existing units.
    if ($unit != NULL && array_key_exists($unit, self::$units)) {
      $count = $this->getIntervalUnits($diff, $unit);
      $duration = self::$units[$unit];
    }
    else {
      // Check for each interval if it appears in the $diff object.
      foreach (self::$units as $attribute => $duration) {
        $count = $diff->$attribute;
        if (0 !== $count) {
          break;
        }
      }
    }

    if ($humanize) {
      return $this->humanize($count, $diff->invert, $duration);
    }

    return $diff->invert ? $count : $count * -1;
  }

  /**
   * Humanize a period of time according the given unit.
   *
   * @param int $count
   *   The number of @units before/after.
   * @param bool $invert
   *   Is 1 if the interval represents a negative time period and 0 otherwise.
   * @param string $unit
   *   A unit from year, minute, day, hour, minute, second.
   *
   * @return string
   *   Humanized period of time.
   */
  protected function humanize($count, $invert, $unit) {

    // Get singular translatable unit of time.
    // phpcs:ignore Drupal.Semantics.FunctionT.NotLiteralString
    $t_unit = $this->t($unit, [], ['context' => 'Time difference unit']);

    // Get plural translatable unit of time.
    // phpcs:ignore Drupal.Semantics.FunctionT.NotLiteralString
    $t_units = $this->t($unit . 's', [], ['context' => 'Time difference unit']);

    // Don't generate pluralized strings when count less than 0.
    if ((int) $count <= 0) {
      if ($invert) {
        return $this->t('in @duration @unit', [
          '@duration' => $count,
          '@unit'     => $t_unit,
        ], ['context' => 'Time difference']);
      }
      return $this->t('@duration @unit ago', [
        '@duration' => $count,
        '@unit'     => $t_unit,
      ], ['context' => 'Time difference']);
    }

    // From here, we need to humanize a potential plural Time difference.
    if ($invert) {
      return $this->formatPlural(
        (int) $count,
        'in @duration @unit',
        'in @duration @units',
        ['@duration' => $count, '@unit' => $t_unit, '@units' => $t_units],
        ['context' => 'Time difference']
      );
    }
    return $this->formatPlural(
      (int) $count,
      '@duration @unit ago',
      '@duration @units ago',
      ['@duration' => $count, '@unit' => $t_unit, '@units' => $t_units],
      ['context' => 'Time difference']
    );
  }

  /**
   * Retrieve the diff between two dates for the given unit.
   *
   * @param \DateInterval $diff
   *   The diff between two dates.
   * @param string $unit
   *   The unit that we want to retrieve diff.
   *
   * @return float
   *   The differences for the given unit.
   */
  protected function getIntervalUnits(\DateInterval $diff, $unit) {
    $total = 0;
    switch ($unit) {
      case 'y':
        $total = ($diff->days + $diff->h / 24) / 365.25;
        break;

      case 'm':
        $total = ($diff->days + $diff->h / 24) / 30;
        break;

      case 'd':
        $total = $diff->days + ($diff->h + $diff->i / 60) / 24;
        break;

      case 'h':
        $total = $diff->days * 24 + $diff->h + $diff->i / 60;
        break;

      case 'i':
        $total = ($diff->days * 24 + $diff->h) * 60 + $diff->i + $diff->s / 60;
        break;

      case 's':
        $total = (($diff->days * 24 + $diff->h) * 60 + $diff->i) * 60 + $diff->s;
        break;
    }
    return $total;
  }

}
