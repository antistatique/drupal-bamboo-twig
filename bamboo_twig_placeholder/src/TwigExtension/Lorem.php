<?php

namespace Drupal\bamboo_twig_placeholder\TwigExtension;

use Drupal\bamboo_twig\TwigExtension\TwigExtensionBase;

/**
 * Provides getter for configs drupal storage as Twig Extensions.
 */
class Lorem extends TwigExtensionBase {

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('bamboo_placeholder_lorem', [$this, 'loremIpsum'], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('bamboo_placeholder_image', [$this, 'loremImage']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig_placeholder.twig.lorem';
  }

  /**
   * Get a sequences of aleatory number of [words\sentences\paragraphs].
   *
   * @param string $type
   *   The type of the sequences. E.g: words\sentences\paragraphs.
   * @param int $size
   *   (optional) When used the function return the exactely
   *    words number of this parameter.
   * @param int $max
   *   (optional) When used the function return
   *   aleatory [words\sentences\paragraphs] into the $size and $max.
   *
   * @return string|null
   *   Return a sequences of aleatory number of [words\sentences\paragraphs].
   */
  public function loremIpsum($type, $size = NULL, $max = NULL) {
    $lorem = $this->container->get('bamboo_twig_placeholder.lorem_ipsum');

    $text = NULL;
    switch ($type) {
      case 'paragraphs':
        $text = $lorem->getParagraphs($size, $max);
        break;

      case 'sentences':
        $text = $lorem->getSentences($size, $max);
        break;

      case 'words':
        $text = $lorem->getWords($size, $max);
        break;
    }

    return $text;
  }

  /**
   *
   */
  public function loremImage($width, $height, $background = NULL, $color = NULL, $label = FALSE, $override = FALSE) {
    $lorem = $this->container->get('bamboo_twig_placeholder.lorem_image');

    $image = $lorem->render($width, $height, $background, $color, $label, $override);

    dump($image);
    die();
  }

}
