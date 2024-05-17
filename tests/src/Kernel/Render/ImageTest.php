<?php

namespace Drupal\Tests\bamboo_twig\Kernel\Render;

use Drupal\Core\Render\Markup;
use Drupal\Core\StreamWrapper\PublicStream;
use Drupal\file\FileInterface;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;

/**
 * @coversDefaultClass \Drupal\bamboo_twig_loader\TwigExtension\Render
 *
 * @group bamboo_twig
 * @group bamboo_twig_render
 */
class ImageTest extends KernelTestBase {
  use MediaTypeCreationTrait;

  /**
   * The Bamboo Twig Render Extension.
   *
   * @var \Drupal\bamboo_twig_loader\TwigExtension\Render
   */
  protected $renderExtension;

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'media',
    'image',
    'user',
    'field',
    'system',
    'file',
    'bamboo_twig',
    'bamboo_twig_loader',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->installEntitySchema('user');
    $this->installEntitySchema('file');
    $this->installSchema('file', 'file_usage');

    // Since Drupal 10.2.0 installing the table sequences with the
    // method KernelTestBase::installSchema() is deprecated.
    if (version_compare(\Drupal::VERSION, '10.2.0', '<')) {
      $this->installSchema('system', ['sequences']);
    }

    $this->installEntitySchema('media');
    $this->installConfig(['field', 'system', 'image', 'file', 'media']);

    // Create an image media type for testing the renderer.
    $this->createMediaType('image', ['id' => 'image']);

    /** @var \Drupal\bamboo_twig_loader\TwigExtension\Render $renderExtension */
    $this->renderExtension = $this->container->get('bamboo_twig_loader.twig.render');

    $this->renderer = $this->container->get('renderer');
  }

  /**
   * Creates and gets test image file.
   *
   * @return \Drupal\file\FileInterface
   *   File object.
   */
  protected function createFile() {
    /** @var \Drupal\Component\PhpStorage\FileStorage $file_storage */
    $file_storage = $this->container->get('entity_type.manager')->getStorage('file');
    /** @var \Drupal\Core\File\FileSystemInterface $file_system */
    $file_system = $this->container->get('file_system');

    $file_system->copy(\Drupal::service('extension.list.module')->getPath('bamboo_twig_test') . '/files/antistatique.jpg', PublicStream::basePath());

    $file = $file_storage->create([
      'uri' => 'public://antistatique.jpg',
      'status' => FileInterface::STATUS_PERMANENT,
    ]);
    $file->save();

    return $file;
  }

  /**
   * Creates and gets test image Media.
   *
   * @return \Drupal\file\FileInterface
   *   File object.
   */
  protected function createMedia() {
    $file = $this->createFile();

    /** @var \Drupal\media\MediaStorage $media_storage */
    $media_storage = $this->container->get('entity_type.manager')->getStorage('media');

    $mediaImage = $media_storage->create([
      'bundle' => 'image',
      'name' => 'Test image',
      'field_media_image' => $file->id(),
    ]);
    $mediaImage->save();

    return $mediaImage;
  }

  /**
   * @covers ::renderImage
   *
   * Cover the usage of
   * {{ bamboo_render_image(1, 'thumbnail') }}.
   * {{ bamboo_render_image(1, 'thumbnail', '') }}.
   * {{ bamboo_render_image(1, 'thumbnail', 'Dignissim (...) primis') }}.
   */
  public function testRenderImageFile() {
    $file = $this->createFile();

    // Ensure {{ bamboo_render_image(1, 'thumbnail') }}.
    $renderer = $this->renderExtension->renderImage($file->id(), 'thumbnail');
    $this->assertEquals([
      '#theme' => 'image_style',
      '#style_name' => 'thumbnail',
      '#uri' => 'public://antistatique.jpg',
      '#alt' => NULL,
    ], $renderer);

    $markup = $this->renderer->renderRoot($renderer);
    $this->assertInstanceOf(Markup::class, $markup);

    // Since Drupal 10.3 the image styles are rendered as webp.
    if (version_compare(\Drupal::VERSION, '10.3', '>=')) {
      $this->assertMatchesRegularExpression('/^<img src=".*public\/antistatique\.jpg.webp\?itok=.*" \/>/', $markup->__toString());
    }
    else {
      $this->assertMatchesRegularExpression('/^<img src=".*public\/antistatique\.jpg\?itok=.*" \/>/', $markup->__toString());
    }

    // Ensure {{ bamboo_render_image(1, 'thumbnail', '') }}.
    $renderer = $this->renderExtension->renderImage($file->id(), 'thumbnail', '');
    $this->assertEquals([
      '#theme' => 'image_style',
      '#style_name' => 'thumbnail',
      '#uri' => 'public://antistatique.jpg',
      '#alt' => '',
    ], $renderer);

    $markup = $this->renderer->renderRoot($renderer);
    $this->assertInstanceOf(Markup::class, $markup);

    // Since Drupal 10.3 the image styles are rendered as webp.
    if (version_compare(\Drupal::VERSION, '10.3', '>=')) {
      $this->assertMatchesRegularExpression('/^<img src=".*public\/antistatique\.jpg.webp\?itok=.*" alt="" \/>/', $markup->__toString());
    }
    else {
      $this->assertMatchesRegularExpression('/^<img src=".*public\/antistatique\.jpg\?itok=.*" alt="" \/>/', $markup->__toString());
    }

    // Ensure {{ bamboo_render_image(1, 'thumbnail', 'Dignissim ... primis') }}.
    $renderer = $this->renderExtension->renderImage($file->id(), 'thumbnail', 'Dignissim dui dolor ipsum sapien habitant primis');
    $this->assertEquals([
      '#theme' => 'image_style',
      '#style_name' => 'thumbnail',
      '#uri' => 'public://antistatique.jpg',
      '#alt' => 'Dignissim dui dolor ipsum sapien habitant primis',
    ], $renderer);

    $markup = $this->renderer->renderRoot($renderer);
    $this->assertInstanceOf(Markup::class, $markup);

    // Since Drupal 10.3 the image styles are rendered as webp.
    if (version_compare(\Drupal::VERSION, '10.3', '>=')) {
      $this->assertMatchesRegularExpression('/^<img src=".*public\/antistatique\.jpg.webp\?itok=.*" alt="Dignissim dui dolor ipsum sapien habitant primis" \/>/', $markup->__toString());
    }
    else {
      $this->assertMatchesRegularExpression('/^<img src=".*public\/antistatique\.jpg\?itok=.*" alt="Dignissim dui dolor ipsum sapien habitant primis" \/>/', $markup->__toString());
    }
  }

  /**
   * @covers ::renderImage
   *
   * Cover the usage of
   * {{ bamboo_render_image(1, 'thumbnail') }}.
   * {{ bamboo_render_image(1, 'thumbnail', '') }}.
   * {{ bamboo_render_image(1, 'thumbnail', 'Dignissim (...) primis') }}.
   */
  public function testRenderImageMedia() {
    $media = $this->createMedia();

    // Ensure {{ bamboo_render_image(1, 'thumbnail') }}.
    $renderer = $this->renderExtension->renderImage($media->field_media_image->target_id, 'thumbnail');
    $this->assertEquals([
      '#theme' => 'image_style',
      '#style_name' => 'thumbnail',
      '#uri' => 'public://antistatique.jpg',
      '#alt' => NULL,
    ], $renderer);

    $markup = $this->renderer->renderRoot($renderer);
    $this->assertInstanceOf(Markup::class, $markup);

    // Since Drupal 10.3 the image styles are rendered as webp.
    if (version_compare(\Drupal::VERSION, '10.3', '>=')) {
      $this->assertMatchesRegularExpression('/^<img src=".*public\/antistatique\.jpg.webp\?itok=.*" \/>/', $markup->__toString());
    }
    else {
      $this->assertMatchesRegularExpression('/^<img src=".*public\/antistatique\.jpg\?itok=.*" \/>/', $markup->__toString());
    }

    // Ensure {{ bamboo_render_image(1, 'thumbnail', '') }}.
    $renderer = $this->renderExtension->renderImage($media->field_media_image->target_id, 'thumbnail', '');
    $this->assertEquals([
      '#theme' => 'image_style',
      '#style_name' => 'thumbnail',
      '#uri' => 'public://antistatique.jpg',
      '#alt' => '',
    ], $renderer);

    $markup = $this->renderer->renderRoot($renderer);
    $this->assertInstanceOf(Markup::class, $markup);

    // Since Drupal 10.3 the image styles are rendered as webp.
    if (version_compare(\Drupal::VERSION, '10.3', '>=')) {
      $this->assertMatchesRegularExpression('/^<img src=".*public\/antistatique\.jpg.webp\?itok=.*" \/>/', $markup->__toString());
    }
    else {
      $this->assertMatchesRegularExpression('/^<img src=".*public\/antistatique\.jpg\?itok=.*" alt="" \/>/', $markup->__toString());
    }

    // Ensure {{ bamboo_render_image(1, 'thumbnail', 'Dignissim ... primis') }}.
    $renderer = $this->renderExtension->renderImage($media->field_media_image->target_id, 'thumbnail', 'Dignissim dui dolor ipsum sapien habitant primis');
    $this->assertEquals([
      '#theme' => 'image_style',
      '#style_name' => 'thumbnail',
      '#uri' => 'public://antistatique.jpg',
      '#alt' => 'Dignissim dui dolor ipsum sapien habitant primis',
    ], $renderer);

    $markup = $this->renderer->renderRoot($renderer);
    $this->assertInstanceOf(Markup::class, $markup);

    // Since Drupal 10.3 the image styles are rendered as webp.
    if (version_compare(\Drupal::VERSION, '10.3', '>=')) {
      $this->assertMatchesRegularExpression('/^<img src=".*public\/antistatique\.jpg.webp\?itok=.*" alt="Dignissim dui dolor ipsum sapien habitant primis" \/>/', $markup->__toString());
    }
    else {
      $this->assertMatchesRegularExpression('/^<img src=".*public\/antistatique\.jpg\?itok=.*" alt="Dignissim dui dolor ipsum sapien habitant primis" \/>/', $markup->__toString());
    }
  }

}
