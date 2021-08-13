<?php

namespace Drupal\Tests\bamboo_twig\Functional;

/**
 * Tests Config twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_config
 */
class BambooTwigConfigTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'bamboo_twig',
    'bamboo_twig_config',
    'bamboo_twig_test',
  ];

  /**
   * Salt used in our tests for one-time login links, cancel links, ...
   *
   * @var string
   */
  private $hashSalt;

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {

    parent::setUp();

    // Used in our tests to retrieve settings.
    $this->hashSalt = $this->container->get('settings')->get('hash_salt');
  }

  /**
   * @covers Drupal\bamboo_twig_config\TwigExtension\Config::getSettings
   */
  public function testGetSettings() {
    $this->drupalGet('/bamboo-twig-config');

    $this->assertSession()->elementExists('css', '.test-configs div.config-settings');
    $this->assertElementContains('.test-configs div.config-settings', $this->hashSalt);
  }

  /**
   * @covers Drupal\bamboo_twig_config\TwigExtension\Config::getConfig
   */
  public function testGetConfig() {
    $this->drupalGet('/bamboo-twig-config');

    $this->assertSession()->elementExists('css', '.test-configs div.config-system');
    $this->assertElementContains('.test-configs div.config-system', 'simpletest@example.com');
  }

  /**
   * @covers Drupal\bamboo_twig_config\TwigExtension\Config::getState
   */
  public function testGetState() {
    $state = $this->container->get('state');
    $this->time = $state->get('system.cron_last');

    $this->drupalGet('/bamboo-twig-config');

    $this->assertSession()->elementExists('css', '.test-configs div.config-state');
    $this->assertElementContains('.test-configs div.config-state', $this->time);
  }

}
