<?php

namespace Drupal\bamboo_twig_test\Controller;

use Drupal\Core\Controller\ControllerBase;
use DateTime;
use Drupal\Component\Datetime\DateTimePlus;
use Drupal\Core\Datetime\DrupalDateTime;

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
  public function testFile() {
    return ['#theme' => 'bamboo_twig_test_file'];
  }

  /**
   * Path page.
   */
  public function testPath() {
    return ['#theme' => 'bamboo_twig_test_path'];
  }

  /**
   * Internationalization page.
   */
  public function testI18n() {
    return [
      '#variables' => [
        'datetime'       => DateTime::createFromFormat('d-m-Y', '24-07-2014'),
        'datetimeplus'   => DateTimePlus::createFromFormat('d-m-Y', '24-07-2014'),
        'drupaldatetime' => DrupalDateTime::createFromFormat('d-m-Y', '24-07-2014'),
      ],
      '#theme' => 'bamboo_twig_test_i18n',
    ];
  }

}
