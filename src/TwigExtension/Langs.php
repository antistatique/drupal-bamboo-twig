<?php

namespace Drupal\bamboo_twig\TwigExtension;

use Drupal\file\Plugin\Field\FieldType\FileFieldItemList;

// Injection.
use Drupal\Core\Language\LanguageManagerInterface;

/**
 * Provides a 'Langs' Twig Extensions.
 */
class Langs extends \Twig_Extension {

  /**
   * Language manager service.
   *
   * @var Drupal\Core\Language\LanguageManagerInterface
   */
  private $language;

  /**
   * TwigExtension constructor.
   */
  public function __construct(LanguageManagerInterface $language) {
    $this->language = $language;
  }

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('get_lang', [$this, 'getLang']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig.twig.langs';
  }

  /**
   * Retrieve the current language.
   */
  public function getLang() {
    return $this->language->getCurrentLanguage()->getId();
  }

}
