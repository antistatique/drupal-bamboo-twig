<?php

namespace Drupal\bamboo_twig_extensions\TwigExtension;

use Drupal\Core\Template\TwigEnvironment;

/**
 * Provides bridge for Text functions and filters.
 *
 * Expose the features of Twig_Extensions_Extension_Date.
 */
class TwigDate extends \Twig_Extension {

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
   *
   * @return string
   *   The converted time.
   */
  public function diff(TwigEnvironment $env, $date, $now = NULL) {
    $extension = new \Twig_Extensions_Extension_Date();
    $filters = $extension->getFilters();

    foreach ($filters as $filter) {
      if ($filter->getName() == 'time_diff') {
        $callable = $filter->getCallable();
        return $callable($env, $date, $now);
      }
    }

    return FALSE;
  }

}
