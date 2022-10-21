<?php

namespace Drupal\bamboo_twig_extensions\TwigExtension;

use Twig\TwigFilter;
use Drupal\Core\Template\TwigEnvironment;
use Twig\Extension\AbstractExtension;

/**
 * Provides bridge for Text functions and filters.
 *
 * Expose the features of Twig_Extensions_Extension_Text.
 */
class TwigText extends AbstractExtension {

  /**
   * List of all Twig functions.
   */
  public function getFilters() {
    return [
      new TwigFilter('bamboo_extensions_truncate', [$this, 'truncate'], ['needs_environment' => TRUE]),
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
    if (mb_strlen($string, $env->getCharset()) > $length) {
      if ($preserve) {
        // If breakpoint is on the last word, return the value w/o separator.
        $breakpoint = mb_strpos($string, ' ', $length, $env->getCharset());
        if (FALSE === $breakpoint) {
          return $string;
        }

        $length = $breakpoint;
      }

      return rtrim(mb_substr($string, 0, $length, $env->getCharset())) . $separator;
    }

    return $string;
  }

}
