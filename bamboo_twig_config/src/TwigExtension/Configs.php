<?php

namespace Drupal\bamboo_twig_config\TwigExtension;

use Drupal\file\Plugin\Field\FieldType\FileFieldItemList;

// Injection.
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\State\StateInterface;

/**
 * Provides a 'Configs' Twig Extensions.
 */
class Configs extends \Twig_Extension {
  /**
   * Config API for storing variables that travel between instances.
   *
   * @var Drupal\Core\Config\ConfigFactory
   */
  private $config;

  /**
   * State API for storing variables that shouldn't travel between instances.
   *
   * @var Drupal\Core\State\StateInterface
   */
  private $state;

  /**
   * TwigExtension constructor.
   */
  public function __construct(ConfigFactory $config, StateInterface $state) {
    $this->config = $config;
    $this->state  = $state;
  }

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('get_config', [$this, 'getConfig']),
      new \Twig_SimpleFunction('get_state', [$this, 'getState']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig.twig.configs';
  }

  /**
   * Load given Config API configuration.
   *
   * @param string $key
   *   The key of the data to retrieve.
   *
   * @return mixed|null
   *   Returns the stored value for a given key, or NULL if no value exists.
   */
  public function getConfig($key) {
    return $this->config->get($key);
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
    return $this->state->get($key);
  }

}
