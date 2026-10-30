<?php

namespace Drupal\Tests\bamboo_twig\Kernel\Render;

use Drupal\bamboo_twig_loader\TwigExtension\Render;
use Drupal\entity_test\Entity\EntityTest as EntityTestEntity;
use Drupal\entity_test\Entity\EntityTestRev;
use Drupal\KernelTests\KernelTestBase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Tests the entity render arrays of Render twig functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_render
 */
#[CoversClass(Render::class)]
#[CoversMethod(Render::class, 'renderEntity')]
#[CoversMethod(Render::class, 'renderEntityRevision')]
#[Group('bamboo_twig')]
#[Group('bamboo_twig_render')]
#[RunTestsInSeparateProcesses]
class EntityTest extends KernelTestBase {

  /**
   * The Bamboo Twig Render extension.
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
    'field',
    'entity_test',
    'bamboo_twig',
    'bamboo_twig_loader',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('user');
    $this->installEntitySchema('entity_test');
    $this->installEntitySchema('entity_test_rev');

    $this->renderExtension = $this->container->get('bamboo_twig_loader.twig.render');
  }

  /**
   * Ensure {{ bamboo_render_entity('entity_test', 1) }} is render cached.
   *
   * @dataProvider providerViewModes
   */
  #[DataProvider('providerViewModes')]
  public function testRenderEntityViewMode(array $arguments, string $expected): void {
    $entity = EntityTestEntity::create(['name' => 'Lorem ipsum']);
    $entity->save();

    $build = $this->renderExtension->renderEntity('entity_test', $entity->id(), ...$arguments);

    $this->assertSame($expected, $build['#view_mode']);
    $this->assertArrayHasKey('keys', $build['#cache'], 'The rendered entity must be render cached.');
  }

  /**
   * Ensure {{ bamboo_render_entity_revision('entity_test_rev', 1) }} is cached.
   *
   * @dataProvider providerViewModes
   */
  #[DataProvider('providerViewModes')]
  public function testRenderEntityRevisionViewMode(array $arguments, string $expected): void {
    $entity = EntityTestRev::create(['name' => 'Lorem ipsum']);
    $entity->save();

    $build = $this->renderExtension->renderEntityRevision('entity_test_rev', $entity->getRevisionId(), ...$arguments);

    $this->assertSame($expected, $build['#view_mode']);
    $this->assertArrayHasKey('keys', $build['#cache'], 'The rendered entity revision must be render cached.');
  }

  /**
   * Provides the view mode arguments and the expected rendered view mode.
   *
   * @return array
   *   The view mode arguments and the expected rendered view mode.
   */
  public static function providerViewModes(): array {
    return [
      'no view mode' => [[], 'default'],
      'empty view mode' => [[''], 'default'],
      'null view mode' => [[NULL], 'default'],
      'default view mode' => [['default'], 'default'],
    ];
  }

}
