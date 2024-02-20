<?php

namespace Drupal\Tests\bamboo_twig\Kernel\Render;

use Drupal\block_content\Entity\BlockContent;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\bamboo_twig\Traits\BlockCreationTrait as BambooBlockCreationTrait;
use Drupal\Tests\block\Traits\BlockCreationTrait;

/**
 * @coversDefaultClass \Drupal\bamboo_twig_loader\TwigExtension\Render
 *
 * @group bamboo_twig
 * @group bamboo_twig_render
 */
class ContentBlockTest extends KernelTestBase {
  use BlockCreationTrait;
  use BambooBlockCreationTrait;

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
    'block_content',
    'field',
    'text',
    'bamboo_twig',
    'bamboo_twig_loader',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('user');
    $this->installEntitySchema('block_content');
    $this->installConfig(['block_content']);

    $this->createBlockContentType('basic');

    /** @var \Drupal\bamboo_twig_loader\TwigExtension\Render $renderExtension */
    $this->renderExtension = $this->container->get('bamboo_twig_loader.twig.render');
  }

  /**
   * @covers ::renderBlock
   *
   * Cover the usage of
   * {{ bamboo_render_block('block_content:ca1f2401-16a3-474b') }}.
   * {{ bamboo_render_block('block_content:ca1f2401-16a3-474b', [], TRUE) }}.
   */
  public function testRenderContentBlock() {
    $block = $this->createBlockContent();
    $this->placeBlock('block_content:' . $block->uuid());

    // Ensure {{ bamboo_render_block('block_content:ca1f2401-1') }}.
    $renderer = $this->renderExtension->renderBlock('block_content:' . $block->uuid(), [], FALSE);
    $this->assertArrayHasKey('#block_content', $renderer);
    $this->assertInstanceOf(BlockContent::class, $renderer['#block_content']);

    // Ensure {{ bamboo_render_block('block_content:ca1f2401-1', [], TRUE) }}.
    $renderer = $this->renderExtension->renderBlock('block_content:' . $block->uuid(), [], TRUE);
    $this->assertArrayHasKey('#theme', $renderer);
    $this->assertEquals('block', $renderer['#theme']);
  }

}
