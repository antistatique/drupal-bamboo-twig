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
   * User page.
   */
  public function testUser() {
    return ['#theme' => 'bamboo_twig_test_user'];
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
