<?php

namespace Drupal\Tests\bamboo_twig\Kernel\Loader;

use Drupal\block\Entity\Block;
use Drupal\block_content\Entity\BlockContent;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\block\Traits\BlockCreationTrait;
use Drupal\Tests\bamboo_twig\Traits\BlockCreationTrait as BambooBlockCreationTrait;

/**
 * @coversDefaultClass \Drupal\bamboo_twig_loader\TwigExtension\Loader
 *
 * @group bamboo_twig
 * @group bamboo_twig_loader
 */
class EntityBlockTest extends KernelTestBase {
  use BlockCreationTrait;
  use BambooBlockCreationTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'system',
    'user',
    'block',
    'block_content',
    'block_test',
    'field',
    'text',
    'bamboo_twig',
    'bamboo_twig_loader',
  ];

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installEntitySchema('user');
    $this->installEntitySchema('block_content');
    $this->installConfig(['block_content']);
    $this->installConfig(['block_test']);

    $this->createBlockContentType('basic');

    /** @var \Drupal\bamboo_twig_loader\TwigExtension\Loader $loaderExtension */
    $this->loaderExtension = $this->container->get('bamboo_twig_loader.twig.loader');
  }

  /**
   * @covers ::loadEntity
   *
   * Cover the usage of {{ bamboo_render_entity('block', 'stark_branding') }}.
   */
  public function testLoaderBlockEntity() {
    $entity = $this->loaderExtension->loadEntity('block', 'test_block');
    $this->assertInstanceOf(Block::class, $entity);
  }

  /**
   * @covers ::loadEntity
   *
   * Cover the usage of {{ bamboo_render_entity('block_content', 1) }}.
   */
  public function testLoaderBlockContentEntity() {
    $block = $this->createBlockContent();
    $entity = $this->loaderExtension->loadEntity('block_content', $block->id());
    $this->assertInstanceOf(BlockContent::class, $entity);
  }

}
