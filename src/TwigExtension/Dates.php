<?php

namespace Drupal\bamboo_twig\TwigExtension;

use Drupal\Core\Datetime\DateFormatter;

/**
 * Provides a 'Dates' Twig Extensions.
 */
class Dates extends \Twig_Extension {


  /**
   * Formats a date, using a date type or a custom date format string.
   *
   * @var Drupal\Core\Datetime\DateFormatter
   */
  private $dateFormatter;

  /**
   * TwigExtension constructor.
   */
  public function __construct(DateFormatter $dateFormatter) {
    $this->dateFormatter = $dateFormatter;
  }

  /**
   * List of all Twig functions.
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('format_date_i18n', array($this, 'dateFormati18n')),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig.twig.dates';
  }

  /**
   * Render a custom date format with Twig.
   *
   * Use the internal helper "format_date" to render the date
   * using the current language for texts.
   *
   * @param mixed $date
   *   A valide DrupalDateTime or DateTime object,
   *   a Y-m-d string or an integer timestamp.
   * @param string $format
   *   A format string.
   *
   * @return string|null
   *   Formatted given date corresponding of given format date.
   */
  public function dateFormati18n($date, $format = 'Y-m-d') {
    if (is_a($date, 'Drupal\Core\Datetime\DrupalDateTime') || is_a($date, 'DateTime')) {
      $timestmap = $date->getTimestamp();
    }
    elseif (\DateTime::createFromFormat('Y-m-d', $date)) {
      $timestmap = strtotime($date);
    }
    else {
      $timestmap = $date;
    }

    // Check the $date is a valid timestmap.
    try {
      $date_format = new \DateTime('@' . $timestmap);
      $timestmap = $date_format->getTimestamp();
    }
    catch (\Exception $e) {
      return NULL;
    }

    return $this->dateFormatter->format($timestmap, "custom", $format);
  }

}
