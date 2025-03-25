<?php

namespace Drupal\bamboo_twig_cacheable\TwigExtension;

use Drupal\bamboo_twig\TwigExtension\TwigExtensionBase;
use Twig\TwigFunction;

/**
 * Provides a 'Cacheable Metadata' Twig Extensions.
 */
class BubbleMetadata extends TwigExtensionBase {

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new TwigFunction('bamboo_attach_cacheable_metadata', $this->attachCacheableMetadata(...)),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig_cacheable.twig.bubble_metadata';
  }

  /**
   * Attaches cacheable metadata to the template, and hence to the response.
   *
   * Allows Twig templates to attach cacheable metadata using:
   * @code
   * {{ bamboo_attach_cacheable_metadata({'contexts': ['user']}) }}
   * @endcode
   *
   * @param array $cacheable_metadata
   *   Array containing cacheable metadata.
   *
   * @return array
   *   Rendered cacheable metadata.
   */
  public function attachCacheableMetadata(array $cacheable_metadata): array {
    $attached_cacheable_metadata = [
      '#cache' => array_intersect_key($cacheable_metadata, [
        'tags' => 0,
        'contexts' => 1,
        'max-age' => 2,
      ]),
    ];
    return $attached_cacheable_metadata;
  }

}
