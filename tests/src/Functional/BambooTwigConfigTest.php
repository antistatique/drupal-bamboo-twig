<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\bamboo_twig_config\TwigExtension\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests Config twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 */
#[CoversClass(Config::class)]
#[CoversMethod(Config::class, 'getSettings')]
#[CoversMethod(Config::class, 'getConfig')]
#[CoversMethod(Config::class, 'getState')]
#[Group('bamboo_twig')]
#[Group('bamboo_twig_functional')]
#[Group('bamboo_twig_config')]
#[RunTestsInSeparateProcesses]
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
   * Tests getSettings() exposes PHP settings values in templates.
   */
  public function testGetSettings() {
    $this->drupalGet('/bamboo-twig-config');

    $this->assertSession()->elementExists('css', '.test-configs div.config-settings');
    $this->assertSession()->elementContains('css', '.test-configs div.config-settings', $this->hashSalt);
  }

  /**
   * Tests getConfig() exposes Drupal configuration values in templates.
   */
  public function testGetConfig() {
    $this->drupalGet('/bamboo-twig-config');

    $this->assertSession()->elementExists('css', '.test-configs div.config-system');
    $this->assertSession()->elementContains('css', '.test-configs div.config-system', 'simpletest@example.com');
  }

  /**
   * Tests getState() exposes Drupal state values in templates.
   */
  public function testGetState() {
    $now = time();
    /** @var \Drupal\Core\State\State $state */
    $state = $this->container->get('state');
    $state->set('system.cron_last', $now);

    $this->drupalGet('/bamboo-twig-config');

    $this->assertSession()->elementExists('css', '.test-configs div.config-state');
    $this->assertSession()->elementContains('css', '.test-configs div.config-state', $now);
  }

}
