<?php

namespace Drupal\Tests\bamboo_twig\Kernel\Render;

use Drupal\block\Entity\Block;
use Drupal\Core\Render\Markup;
use Drupal\KernelTests\KernelTestBase;

/**
 * @coversDefaultClass \Drupal\bamboo_twig_loader\TwigExtension\Render
 *
 * @group bamboo_twig
 * @group bamboo_twig_render
 */
class PluginBlockTest extends KernelTestBase {

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * The Bamboo Twig Render Extension.
   *
   * @var \Drupal\bamboo_twig_loader\TwigExtension\Render
   */
  protected $renderExtension;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'user',
    'block',
    'block_test',
    'bamboo_twig',
    'bamboo_twig_loader',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    /** @var \Drupal\bamboo_twig_loader\TwigExtension\Render $renderExtension */
    $this->renderExtension = $this->container->get('bamboo_twig_loader.twig.render');

    $this->renderer = $this->container->get('renderer');
  }

  /**
   * @covers ::renderBlock
   *
   * Cover the usage of {{ bamboo_render_block('system_powered_by_block') }}.
   */
  public function testRenderSystemPluginBlock() {
    Block::create([
      'id' => $this->randomMachineName(),
      'plugin' => 'system_powered_by_block',
    ]);

    // Ensure {{ bamboo_render_block('system_powered_by_block') }}.
    $renderer = $this->renderExtension->renderBlock('system_powered_by_block', [], FALSE);
    $this->assertSame(['#markup'], array_keys($renderer));
    $markup = $this->renderer->renderRoot($renderer);
    $this->assertEquals('<span>Powered by <a href="https://www.drupal.org">Drupal</a></span>', $markup->__toString());

    // Ensure {{ bamboo_render_block('test_settings_validation', [], TRUE) }}.
    $renderer = $this->renderExtension->renderBlock('system_powered_by_block', [], TRUE);
    $this->assertSame([
      '#theme',
      '#attributes',
      '#contextual_links',
      '#configuration',
      '#plugin_id',
      '#base_plugin_id',
      '#derivative_plugin_id',
      'content',
    ], array_keys($renderer));

    $markup = $this->renderer->renderRoot($renderer);
    $expected_output = <<<HTML
<div>
    <span>Powered by <a href="https://www.drupal.org">Drupal</a></span>
</div>
HTML;
    self::assertXmlStringEqualsXmlString($expected_output, $markup->__toString());
  }

  /**
   * @covers ::renderBlock
   *
   * Cover the usage of {{ bamboo_render_block('test_settings_validation') }}.
   */
  public function testRenderCustomPluginBlock() {
    // Ensure {{ bamboo_render_block('test_settings_validation', [], FALSE) }}.
    $renderer = $this->renderExtension->renderBlock('test_settings_validation');
    $this->assertSame(['#markup'], array_keys($renderer));
    $markup = $this->renderer->renderRoot($renderer);
    $this->assertArrayHasKey('#markup', $renderer);
    $this->assertInstanceOf(Markup::class, $markup);
    $this->assertEquals('foo', $markup->__toString());

    // Ensure {{ bamboo_render_block('test_settings_validation', [], TRUE) }}.
    $renderer = $this->renderExtension->renderBlock('test_settings_validation', [], TRUE);
    $this->assertSame([
      '#theme',
      '#attributes',
      '#contextual_links',
      '#configuration',
      '#plugin_id',
      '#base_plugin_id',
      '#derivative_plugin_id',
      'content',
    ], array_keys($renderer));

    $markup = $this->renderer->renderRoot($renderer);
    $this->assertInstanceOf(Markup::class, $markup);

    $expected_output = <<<HTML
<div>
  
    
      foo
  </div>
HTML;
    $this->assertXmlStringEqualsXmlString($expected_output, $markup->__toString());
  }

}
