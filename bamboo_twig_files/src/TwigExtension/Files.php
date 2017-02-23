<?php

namespace Drupal\bamboo_twig_files\TwigExtension;

use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;

/**
 * Provides a 'Files' Twig Extensions.
 */
class Files extends \Twig_Extension {

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('theme_url', array($this, 'themeUrl'), array('is_safe' => array('html'))),
    ];
  }

  /**
   * List of all Twig functions.
   */
  public function getFilters() {
    return [
      new \Twig_SimpleFilter('extension_guesser', array($this, 'extensionGuesser')),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig.twig.files';
  }

  /**
   * Render a custom date format with Twig.
   *
   * Use the internal helper "format_date" to render the date
   * using the current language for texts.
   */
  public static function extensionGuesser($mime_type) {
    $guesser = ExtensionGuesser::getInstance();
    return $guesser->guess($mime_type);
  }

  /**
   * Generate an absolute url to the given theme.
   *
   * @param  string   $theme
   *   Theme name.
   * @param  string   $file
   *   File path from theme root.
   * @return string
   *   Absolute url to the given file in the theme.
   */
  public static function themeUrl($theme, $file) {
    return file_create_url(drupal_get_path('theme', $theme) . '/' . $file);
  }

}
