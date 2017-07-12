<?php

namespace Drupal\bamboo_twig_extensions\TwigExtension;

use Drupal\Core\Template\TwigEnvironment;

/**
 * Provides bridge for Text functions and filters.
 *
 * Expose the features of Twig_Extensions_Extension_Text.
 */
class TwigText extends \Twig_Extension {

  /**
   * List of all Twig functions.
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('bamboo_extensions_truncate', [$this, 'truncate'], ['needs_environment' => TRUE]),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig_extensions.twig.text';
  }

  /**
   * Truncate a string.
   *
   * Can't use the Twig filter callback cause the truncate function is
   * actually declared as a global function and not method of
   * Twig_Extensions_Extension_Text.
   *
   * @param \Drupal\Core\Template\TwigEnvironment $env
   *   A Twig_Environment instance.
   * @param string $string
   *   The input string. Must be one character or longer.
   * @param int $length
   *   The string returned will contain at most length chars from beginning.
   * @param bool $preserve
   *   Preserving whole words or not.
   * @param string $separator
   *   The ellipsis to use.
   *
   * @return string|bool
   *   Returns the extracted part of string; or FALSE on failure,
   *   or an empty string.
   */
  public function truncate(TwigEnvironment $env, $string, $length = 30, $preserve = FALSE, $separator = '...') {
    $extension = new \Twig_Extensions_Extension_Text();
    $filters = $extension->getFilters();

    foreach ($filters as $filter) {
      if ($filter->getName() == 'truncate') {
        $callable = $filter->getCallable();
        return $callable($env, $string, $length, $preserve, $separator);
      }
    }

    return FALSE;
  }

}
