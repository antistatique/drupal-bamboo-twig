<?php

namespace Drupal\bamboo_twig_images\TwigExtension;

use Drupal\file\Plugin\Field\FieldType\FileFieldItemList;

// Injection.
use Drupal\Core\Image\ImageFactory;
use Drupal\disrupt_tools\Service\ImageStyleGenerator;

/**
 * Provides a 'Images' Twig Extensions.
 */
class Images extends \Twig_Extension {
  /**
   * Provides a factory for image objects.
   *
   * @var Drupal\Core\Image\ImageFactory
   */
  private $imageFactory;

  /**
   * ImageStyleGenerator Service.
   *
   * @var Drupal\disrupt_tools\Service\ImageStyleGenerator
   */
  private $imageStyleGenerator;

  /**
   * TwigExtension constructor.
   */
  public function __construct(ImageFactory $imageFactory, ImageStyleGenerator $imageStyleGenerator) {
    $this->imageFactory        = $imageFactory;
    $this->imageStyleGenerator = $imageStyleGenerator;
  }

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('image_style_field', [$this, 'imageStyleField']),
      new \Twig_SimpleFunction('image_style_file', [$this, 'imageStyleFile']),
      new \Twig_SimpleFunction('get_image', [$this, 'getImage']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig.twig.images';
  }

  /**
   * Generate Image Style, with responsive format.
   *
   * @param Drupal\file\Plugin\Field\FieldType\FileFieldItemList $field
   *   Field File Entity to retrieve cover and generate it.
   * @param array $styles
   *   Styles to be generated.
   *
   * @return array
   *   Generated link of styles
   */
  public function imageStyleField(FileFieldItemList $field, array $styles) {
    return $this->imageStyleGenerator->fromField($field, $styles);
  }

  /**
   * Generate Image Style, with responsive format.
   *
   * @param int $fid
   *   File id to generate.
   * @param array $styles
   *   Styles to be generated.
   *
   * @return array
   *   Generated link of styles
   */
  public function imageStyleFile($fid, array $styles) {
    return $this->imageStyleGenerator->fromFile($fid, $styles);
  }

  /**
   * Load an image from file uri.
   *
   * @param string $file_uri
   *    File URI on the current server.
   *
   * @return \Drupal\Core\Image\ImageInterface
   *    An Image object.
   */
  public function getImage($file_uri) {
    return $this->imageFactory->get($file_uri);
  }

}
