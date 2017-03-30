<?php

namespace Drupal\bamboo_twig_test\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * TestsController.
 */
class TestsController extends ControllerBase {

  /**
   * Loaders page.
   */
  public function loaders() {
    return ['#theme' => 'bamboo_twig_test_loaders'];
  }

  /**
   * Security page.
   */
  public function security() {
    return ['#theme' => 'bamboo_twig_test_security'];
  }

  /**
   * Config page for testing config Twig Extensions.
   */
  public function testConfig() {
    return ['#theme' => 'bamboo_twig_test_config'];
  }

  /**
   * File page.
   */
  public function files() {
    return ['#theme' => 'bamboo_twig_test_files'];
  }

}
