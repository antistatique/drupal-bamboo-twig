<?php

namespace Drupal\bamboo_twig_config\TwigExtension;

use Drupal\bamboo_twig\TwigExtension\TwigExtensionBase;

/**
 * Provides getter for configs drupal storage as Twig Extensions.
 */
class Config extends TwigExtensionBase {

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('bamboo_config_get', [$this, 'getConfig']),
      new \Twig_SimpleFunction('bamboo_state_get', [$this, 'getState']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig_config.twig.config';
  }

  /**
   * Load given Config API configuration.
   *
   * @param string $key
   *   The key of the data to retrieve.
   * @param string $name
   *   The name of config to retrieve.
   *
   * @return mixed|null
   *   Returns the stored value for a given key, or NULL if no value exists.
   */
  public function getConfig($key, $name) {
    return $this->getConfigFactory()->get($key)->get($name);
  }

  /**
   * Load given State API configuration.
   *
   * @param string $key
   *   The key of the data to retrieve.
   *
   * @return mixed|null
   *   Returns the stored value for a given key, or NULL if no value exists.
   */
  public function getState($key) {
    return $this->getStateFactory()->get($key);
  }

}
