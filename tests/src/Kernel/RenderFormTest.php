<?php

namespace Drupal\Tests\bamboo_twig\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests Form Rendering twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_render_form
 */
class RenderFormTest extends KernelTestBase {

  /**
   * The Bamboo Twig Render Extension.
   *
   * @var \Drupal\bamboo_twig_loader\TwigExtension\Render
   */
  protected $renderExtension;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'bamboo_twig',
    'bamboo_twig_test',
    'bamboo_twig_loader',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->renderExtension = $this->container->get('bamboo_twig_loader.twig.render');
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderForm
   */
  public function testFormWithoutParam() {
    $form = $this->renderExtension->renderForm('bamboo_twig_test', 'BasicForm');
    self::assertArrayHasKey('checkbox', $form);
    self::assertArrayHasKey('form_id', $form);
    self::assertSame('bamboo_twig_test_basic_form', $form['form_id']['#value']);
  }

  /**
   * @covers Drupal\bamboo_twig_loader\TwigExtension\Render::renderForm
   *
   * @dataProvider providerFormParameters
   */
  public function testFormWithParams(string $form_class, string $expected_form_id, $args, $expected_default_value) {
    $form = $this->renderExtension->renderForm('bamboo_twig_test', $form_class, $args);
    self::assertArrayHasKey('text', $form);
    self::assertArrayHasKey('form_id', $form);
    self::assertSame($expected_form_id, $form['form_id']['#value']);
    self::assertSame($expected_default_value, $form['text']['#default_value']);
  }

  /**
   * Tests the renderForm() method with parameter(s).
   */
  public static function providerFormParameters() {
    yield [
      'ArrayParamForm', 'bamboo_twig_test_array_form', [
        'text' => 'foobar',
      ], 'foobar',
    ];
    yield [
      'ObjectParamForm', 'bamboo_twig_test_object_form', (object) [
        'text' => 'foobar',
      ], 'foobar',
    ];
    yield ['MixedParamForm', 'bamboo_twig_test_mixed_form', 'foobar', 'foobar'];
    yield ['MixedParamForm', 'bamboo_twig_test_mixed_form', 12, 12];
    yield ['MixedParamForm', 'bamboo_twig_test_mixed_form', 12.4, 12.4];
    yield [
      'MixedParamForm', 'bamboo_twig_test_mixed_form', [
        'text' => 'foobar',
      ], ['text' => 'foobar'],
    ];
    yield ['MixedParamForm', 'bamboo_twig_test_mixed_form', NULL, NULL];
  }

}
