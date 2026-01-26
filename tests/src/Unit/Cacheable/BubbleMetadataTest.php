<?php

namespace Drupal\Tests\bamboo_twig\Unit\Cacheable;

use Drupal\bamboo_twig_cacheable\TwigExtension\BubbleMetadata;
use Drupal\Tests\UnitTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @coversDefaultClass \Drupal\bamboo_twig_cacheable\TwigExtension\BubbleMetadata
 *
 * @group bamboo_twig
 * @group bamboo_twig_cacheable
 * @group bamboo_twig_cacheable_unit
 */
class BubbleMetadataTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'bamboo_twig',
    'bamboo_twig_cacheable',
  ];

  /**
   * @covers ::attachCacheableMetadata
   *
   * @dataProvider providerAttachCacheableMetadata
   */
  public function testAttachCacheableMetadata(array $input, array $expected) {
    $container = $this->createMock(ContainerInterface::class);
    $twig_extension = new BubbleMetadata($container);
    $output = $twig_extension->attachCacheableMetadata($input);
    self::assertSame($expected, $output);
  }

  /**
   * Data provider for: testAttachCacheableMetadata().
   */
  public static function providerAttachCacheableMetadata(): iterable {
    return [
          [
              [
                'contexts' => ['kitten.type'],
                'tags' => ['entity.kitten.1'],
                'max-age' => 10,
              ],
              [
                '#cache' => [
                  'contexts' => ['kitten.type'],
                  'tags' => ['entity.kitten.1'],
                  'max-age' => 10,
                ],
              ],
          ],
          [
              [
                'contexts' => ['kitten.type'],
                'bad_array_key' => 'cat.type',
              ],
              [
                '#cache' => [
                  'contexts' => ['kitten.type'],
                ],
              ],
          ],
    ];
  }

}
