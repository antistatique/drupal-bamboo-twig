<?php

namespace Drupal\bamboo_twig_extensions\TwigExtension;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

/**
 * Provides bridge for Array functions and filters.
 *
 * Expose the features of Twig_Extensions_Extension_Array.
 */
class TwigArray extends AbstractExtension {

  /**
   * List of all Twig functions.
   */
  public function getFilters() {
    return [
      new TwigFilter('bamboo_extensions_shuffle', [$this, 'shuffle']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig_extensions.twig.array';
  }

  /**
   * Shuffles an array.
   *
   * Can't use the Twig filter callback cause the shuffle function is
   * actually declared as a global function and not method of
   * Twig_Extensions_Extension_Array.
   *
   * @param array|\Traversable $array
   *   An array.
   *
   * @return array|bool
   *   The shuffled array; or FALSE on failure.
   */
  public function shuffle($array) {
    if ($array instanceof \Traversable) {
      $array = iterator_to_array($array, FALSE);
    }

    shuffle($array);

    return $array;
  }

}
