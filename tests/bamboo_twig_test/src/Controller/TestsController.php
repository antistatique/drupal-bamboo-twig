<?php

namespace Drupal\bamboo_twig_test\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * TestsController.
 */
class TestsController extends ControllerBase {

  /**
   * Loader page.
   */
  public function testLoader() {
    return ['#theme' => 'bamboo_twig_test_loader'];
  }

  /**
   * Render page.
   */
  public function testRender() {
    return ['#theme' => 'bamboo_twig_test_render'];
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

}
