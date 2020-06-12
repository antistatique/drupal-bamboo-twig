<?php

namespace Drupal\bamboo_twig_config\TwigExtension;

use Twig\TwigFunction;
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
      new TwigFunction('bamboo_settings_get', [$this, 'getSettings']),
      new TwigFunction('bamboo_config_get', [$this, 'getConfig']),
      new TwigFunction('bamboo_state_get', [$this, 'getState']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig_config.twig.config';
  }

  /**
   * Load given Config Settings from the settings.php file.
   *
   * @param string $key
   *   The key of the data to retrieve.
   *
   * @return mixed|null
   *   Returns the stored value for a given key, or NULL if no value exists.
   */
  public function getSettings($key) {
    return $this->getSettingsSingleton()->get($key);
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
