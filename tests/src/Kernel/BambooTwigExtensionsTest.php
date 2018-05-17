<?php

namespace Drupal\Tests\bamboo_twig\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests Extensions twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_extensions
 */
class BambooTwigExtensionsTest extends KernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'bamboo_twig',
    'bamboo_twig_extensions',
  ];

  /**
   * @covers \Drupal\bamboo_twig_extensions\TwigExtension\TwigText::strpad
   *
   * @dataProvider providerTestTextStrpad
   */
  public function testTextStrpad($input, $pad_length, $pad_string, $pad_type, $expected) {
    /** @var \Drupal\bamboo_twig_extensions\TwigExtension\TwigText $extension */
    $extension = \Drupal::service('bamboo_twig_extensions.twig.text');

    $result = $extension->strpad($input, $pad_length, $pad_string, $pad_type);
    $this->assertEquals($result, $expected);
  }

  /**
   * Data provider for testTextStrpad().
   *
   * @return array
   */
  public function providerTestTextStrpad() {
    return [
      ['Alien', 10, " ", STR_PAD_RIGHT, 'Alien     '],
      ['Alien', 10, "-=", STR_PAD_LEFT, '-=-=-Alien'],
      ['Alien', 6, "___", STR_PAD_RIGHT, 'Alien_'],
      ['Alien', 3, "*", STR_PAD_RIGHT, 'Alien'],

      // Since the default pad_type is STR_PAD_RIGHT. using STR_PAD_BOTH
      // were always favor in the right pad if the required number of padding
      // characters can't be evenly divided.
      ['Alien', 10, "pp", STR_PAD_BOTH, 'ppAlienppp'],
      ['Alien', 6, "p", STR_PAD_BOTH, 'Alienp'],
      ['Alien', 8, "p", STR_PAD_BOTH, 'pAlienpp'],
    ];
  }

}
