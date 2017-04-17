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

  /**
   * Twig Extensions page.
   */
  public function extensions() {
    return ['#theme' => 'bamboo_twig_test_extensions'];
  }

}
