<?php

namespace Drupal\bamboo_twig_extensions\TwigExtension;

/**
 * Provides bridge for Array functions and filters.
 *
 * Expose the features of Twig_Extensions_Extension_Array.
 */
class TwigArray extends \Twig_Extension {

  /**
   * List of all Twig functions.
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('bamboo_extensions_shuffle', [$this, 'shuffle']),
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
   * @param array|\Traversable $iterator
   *   An array.
   *
   * @return array|bool
   *   The shuffled array; or FALSE on failure.
   */
  public function shuffle($iterator) {
    $extension = new \Twig_Extensions_Extension_Array();
    $filters = $extension->getFilters();

    foreach ($filters as $filter) {
      if ($filter->getName() == 'shuffle') {
        $callable = $filter->getCallable();
        return $callable($iterator);
      }
    }

    return FALSE;
  }

}
