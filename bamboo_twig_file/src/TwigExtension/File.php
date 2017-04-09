<?php

namespace Drupal\bamboo_twig_file\TwigExtension;

use Drupal\bamboo_twig\TwigExtension\TwigExtensionBase;

/**
 * Provides a 'File' Twig Extensions.
 */
class File extends TwigExtensionBase {

  /**
   * List of all Twig functions.
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('bamboo_file_extension_guesser', [$this, 'extensionGuesser']),
    ];
  }

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('bamboo_file_url_absolute', [$this, 'urlAbsolute']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig_file.twig.file';
  }

  /**
   * Render a custom date format with Twig.
   *
   * Use the internal helper "format_date" to render the date
   * using the current language for texts.
   */
  public function extensionGuesser($mime_type) {
    return $this->getExtensionGuesser()->guess($mime_type);
  }

  /**
   * Creates a web-accessible URL for a stream to an external or local file.
   *
   * @param string $uri
   *   The URI to a file for which we need an external URL,
   *   or the path to a shipped file.
   *
   * @return string
   *   A string containing a URL that may be used to access the file.
   *   If the provided string already contains a preceding 'http',
   *   'https', or '/', nothing is done and the same string is returned.
   *   If a stream wrapper could not be found to generate an external URL,
   *   then FALSE is returned
   */
  public function urlAbsolute($uri) {
    return file_create_url($uri);
  }

}
