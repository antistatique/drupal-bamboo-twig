<?php

namespace Drupal\bamboo_twig_path\TwigExtension;

/**
 * Provides a 'Path' Twig Extensions.
 */
class Path extends \Twig_Extension {

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('bamboo_path_system', [$this, 'getSystemPath']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig_path.twig.path';
  }

  /**
   * Returns the path to a system item (module, theme, etc.).
   *
   * @param string $type
   *   The type of the item; one of 'core', 'profile', 'module', 'theme',
   *   or 'theme_engine'.
   * @param string $name
   *   The name of the item for which the path is requested.
   *   Ignored for $type 'core'.
   *
   * @return string
   *   The path to the requested item or an empty string
   *   if the item is not found.
   */
  public function getSystemPath($type, $name = NULL) {
    return drupal_get_path($type, $name);
  }

}
