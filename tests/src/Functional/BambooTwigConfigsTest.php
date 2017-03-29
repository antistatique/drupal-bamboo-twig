<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests Configs twig filters and functions.
 *
 * @group bamboo_twig
 */
class BambooTwigConfigsTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bamboo_twig',
    'bamboo_twig_config',
    'bamboo_twig_test',
  ];

  /**
   * @covers Drupal\bamboo_twig_config\TwigExtension\Configs::getConfig
   */
  public function testGetConfig() {
    $this->drupalGet('/bamboo-twig-configs');

    $this->assertElementPresent('.test-configs div.config-system');
    $this->assertElementContains('.test-configs div.config-system', 'simpletest@example.com');
  }

  /**
   * @covers Drupal\bamboo_twig_config\TwigExtension\Configs::getState
   */
  public function testGetState() {
    $state = $this->container->get('state');
    $this->time = $state->get('system.cron_last');

    $this->drupalGet('/bamboo-twig-configs');

    $this->assertElementPresent('.test-configs div.config-state');
    $this->assertElementContains('.test-configs div.config-state', $this->time);
  }

}
