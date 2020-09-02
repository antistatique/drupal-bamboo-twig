<?php

namespace Drupal\bamboo_twig_i18n\TwigExtension;

use Twig\TwigFilter;
use Twig\TwigFunction;
use Drupal\bamboo_twig\TwigExtension\TwigExtensionBase;
use Drupal\Core\Template\TwigEnvironment;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides some 'Internationalization' Twig Extensions.
 */
class I18n extends TwigExtensionBase {

  /**
   * List of all Twig functions.
   */
  public function getFilters() {
    return [
      new TwigFilter('bamboo_i18n_format_date', [$this, 'formatDate'], ['needs_environment' => TRUE]),
      new TwigFilter('bamboo_i18n_get_translation', [$this, 'getTranslation']),
    ];
  }

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new TwigFunction('bamboo_i18n_current_lang', [$this, 'getCurrentLanguage']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig_i18n.twig.i18n';
  }

  /**
   * Retrieve the current language.
   */
  public function getCurrentLanguage() {
    return $this->getLanguageManager()->getCurrentLanguage()->getId();
  }

  /**
   * Render a custom date format with Twig.
   *
   * Use the internal helper "format_date" to render the date
   * using the current language for texts.
   *
   * @param \Drupal\Core\Template\TwigEnvironment $env
   *   A Twig_Environment instance.
   * @param int|string|DateTime $date
   *   A string, integer timestamp or DateTime object to convert.
   * @param string $type
   *   (optional) The format to use, one of:
   *   - One of the built-in formats: 'short', 'medium',
   *     'long', 'html_datetime', 'html_date', 'html_time',
   *     'html_yearless_date', 'html_week', 'html_month', 'html_year'.
   *   - The name of a date type defined by a date format config entity.
   *   - The machine name of an administrator-defined date format.
   *   - 'custom', to use $format.
   *   Defaults to 'medium'.
   * @param string $format
   *   (optional) If $type is 'custom', a PHP date format string suitable for
   *   input to date(). Use a backslash to escape ordinary text, so it does not
   *   get interpreted as date format characters.
   * @param string|null $timezone
   *   (optional) Time zone identifier, as described at
   *   http://php.net/manual/timezones.php Defaults to the time zone used to
   *   display the page.
   * @param string|null $langcode
   *   (optional) Language code to translate to. NULL (default) means to use
   *   the user interface language for the page.
   *
   * @return string|null
   *   A translated date string in the requested format. Since the format may
   *   contain user input, this value should be escaped when output.
   */
  public function formatDate(TwigEnvironment $env, $date, $type = 'medium', $format = '', $timezone = NULL, $langcode = NULL) {
    $date = twig_date_converter($env, $date);
    if ($date instanceof \DateTime) {
      return $this->getDateFormatter()->format($date->getTimestamp(), $type, $format, $timezone, $langcode);
    }
    return NULL;
  }

  /**
   * Gets a translation of the entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity to translate.
   * @param string|null $langcode
   *   (optional) The language code of the translation to get.
   *   NULL (default) means to use the user interface language for the page.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   An entity object when translations exists or the original entity.
   */
  public function getTranslation(EntityInterface $entity, $langcode = NULL) {
    $entityRepository = $this->getEntityRepository();

    if (!$langcode) {
      $langcode = $this->getCurrentLanguage();
    }

    return $entityRepository->getTranslationFromContext($entity, $langcode);
  }

}
