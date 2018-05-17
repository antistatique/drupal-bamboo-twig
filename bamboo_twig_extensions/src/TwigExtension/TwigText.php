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
      new \Twig_SimpleFilter('bamboo_extensions_strpad', [$this, 'strpad']),
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

  /**
   * Pad a string to a certain length with another string.
   *
   * @see str_pad()
   *
   * @param string $input
   *   The input string.
   * @param int $pad_length
   *   If the value of pad_length is negative, less than, or equal to
   *   the length of the input string, no padding takes place,
   *   and input will be returned.
   * @param string $pad_string
   *   The pad_string may be truncated if the required number of padding
   *   characters can't be evenly divided by the pad_string's length.
   * @param string $pad_type
   *    Optional argument pad_type can be STR_PAD_RIGHT, STR_PAD_LEFT, or
   *    STR_PAD_BOTH. When not specified it is assumed to be STR_PAD_RIGHT.
   *
   * @return string
   *   Returns the padded string.
   */
  public function strpad($input, $pad_length, $pad_string = " ", $pad_type = STR_PAD_RIGHT) {
    return str_pad($input, $pad_length, $pad_string, $pad_type);
  }
}
