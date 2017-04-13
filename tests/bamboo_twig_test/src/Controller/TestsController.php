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
   * Config page.
   */
  public function configs() {
    return ['#theme' => 'bamboo_twig_test_configs'];
  }

  /**
   * File page.
   */
  public function files() {
    return ['#theme' => 'bamboo_twig_test_files'];
  }

  /**
   * Placeholder page.
   */
  public function testPlaceholder() {
    return ['#theme' => 'bamboo_twig_test_placeholder'];
  }

}
