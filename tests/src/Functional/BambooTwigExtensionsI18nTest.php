<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\Component\Gettext\PoItem;

/**
 * Tests Extensions i18n of twig filters and functions.
 *
 * @group bamboo_twig
 * @group bamboo_twig_functional
 * @group bamboo_twig_extensions
 * @group bamboo_twig_extensions_i18n
 */
class BambooTwigExtensionsI18nTest extends BambooTwigTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'locale',
    'language',
    'bamboo_twig',
    'bamboo_twig_extensions',
    'bamboo_twig_test',
  ];

  /**
   * {@inheritdoc}
   */
  public function setUp(): void {

    parent::setUp();

    /** @var \Drupal\Core\Entity\EntityTypeManager $entityTypeManager */
    $this->entityTypeManager = $this->container->get('entity_type.manager');

    $this->setUpLanguages();
    $this->setUpTranslations();
  }

  /**
   * Sets up languages needed for test.
   */
  protected function setUpLanguages() {
    // English (en) is created by default.
    ConfigurableLanguage::createFromLangcode('fr')->save();
    ConfigurableLanguage::createFromLangcode('de')->save();
  }

  /**
   * Sets up translations strings needed for test.
   */
  protected function setUpTranslations() {
    /** @var \Drupal\locale\StringStorageInterface $localStorage */
    $localStorage = $this->container->get('locale.storage');

    // Second/Seconds.
    $second = $localStorage->createString([
      'source'  => 'second',
      'context' => 'Time difference unit',
    ]);
    $second->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $second->lid,
      'language'    => 'fr',
      'translation' => 'seconde',
    ])->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $second->lid,
      'language'    => 'de',
      'translation' => 'Sekunde',
    ])->save();
    $seconds = $localStorage->createString([
      'source'  => 'seconds',
      'context' => 'Time difference unit',
    ]);
    $seconds->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $seconds->lid,
      'language'    => 'fr',
      'translation' => 'secondes',
    ])->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $seconds->lid,
      'language'    => 'de',
      'translation' => 'Sekunden',
    ])->save();

    // Minute/Minutes.
    $minute = $localStorage->createString([
      'source'  => 'minute',
      'context' => 'Time difference unit',
    ]);
    $minute->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $minute->lid,
      'language'    => 'fr',
      'translation' => 'minute',
    ])->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $minute->lid,
      'language'    => 'de',
      'translation' => 'Minute',
    ])->save();
    $minutes = $localStorage->createString([
      'source'  => 'minutes',
      'context' => 'Time difference unit',
    ]);
    $minutes->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $minutes->lid,
      'language'    => 'fr',
      'translation' => 'minutes',
    ])->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $minutes->lid,
      'language'    => 'de',
      'translation' => 'Minuten',
    ])->save();

    // Hour/Hours.
    $hour = $localStorage->createString([
      'source'  => 'hour',
      'context' => 'Time difference unit',
    ]);
    $hour->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $hour->lid,
      'language'    => 'fr',
      'translation' => 'heure',
    ])->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $hour->lid,
      'language'    => 'de',
      'translation' => 'Stunde',
    ])->save();
    $hours = $localStorage->createString([
      'source'  => 'hours',
      'context' => 'Time difference unit',
    ]);
    $hours->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $hours->lid,
      'language'    => 'fr',
      'translation' => 'heures',
    ])->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $hours->lid,
      'language'    => 'de',
      'translation' => 'Stunden',
    ])->save();

    // Day & Days.
    $day = $localStorage->createString([
      'source'  => 'day',
      'context' => 'Time difference unit',
    ]);
    $day->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $day->lid,
      'language'    => 'fr',
      'translation' => 'jour',
    ])->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $day->lid,
      'language'    => 'de',
      'translation' => 'Tag',
    ])->save();
    $days = $localStorage->createString([
      'source'  => 'days',
      'context' => 'Time difference unit',
    ]);
    $days->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $days->lid,
      'language'    => 'fr',
      'translation' => 'jours',
    ])->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $days->lid,
      'language'    => 'de',
      'translation' => 'Tagen',
    ])->save();

    // Month/Months.
    $month = $localStorage->createString([
      'source'  => 'month',
      'context' => 'Time difference unit',
    ]);
    $month->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $month->lid,
      'language'    => 'fr',
      'translation' => 'mois',
    ])->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $month->lid,
      'language'    => 'de',
      'translation' => 'Monat',
    ])->save();
    $months = $localStorage->createString([
      'source'  => 'months',
      'context' => 'Time difference unit',
    ]);
    $months->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $months->lid,
      'language'    => 'fr',
      'translation' => 'mois',
    ])->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $months->lid,
      'language'    => 'de',
      'translation' => 'Monaten',
    ])->save();

    // Year/Years.
    $year = $localStorage->createString([
      'source'  => 'year',
      'context' => 'Time difference unit',
    ]);
    $year->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $year->lid,
      'language'    => 'fr',
      'translation' => 'an',
    ])->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $year->lid,
      'language'    => 'de',
      'translation' => 'Jahr',
    ])->save();
    $years = $localStorage->createString([
      'source'  => 'years',
      'context' => 'Time difference unit',
    ]);
    $years->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $years->lid,
      'language'    => 'fr',
      'translation' => 'ans',
    ])->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $years->lid,
      'language'    => 'de',
      'translation' => 'Jahren',
    ])->save();

    // Durations & Unit - Futur.
    $in_duration_unit = $localStorage->createString([
      'source'  => 'in @duration @unit' . PoItem::DELIMITER . 'in @duration @units',
      'context' => 'Time difference',
    ]);
    $in_duration_unit->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $in_duration_unit->lid,
      'language'    => 'fr',
      'translation' => 'dans @duration @unit' . PoItem::DELIMITER . 'dans @duration @units',
    ])->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $in_duration_unit->lid,
      'language'    => 'de',
      'translation' => 'in @duration @unit' . PoItem::DELIMITER . 'in @duration @units',
    ])->save();

    // Durations & Unit - Past.
    $duration_unit_ago = $localStorage->createString([
      'source'  => '@duration @unit ago' . PoItem::DELIMITER . '@duration @units ago',
      'context' => 'Time difference',
    ]);
    $duration_unit_ago->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $duration_unit_ago->lid,
      'language'    => 'fr',
      'translation' => 'il y a @duration @unit' . PoItem::DELIMITER . 'il y a @duration @units',
    ])->save();
    $this->translationsStrings[] = $localStorage->createTranslation([
      'lid'         => $duration_unit_ago->lid,
      'language'    => 'de',
      'translation' => 'vor @duration @unit' . PoItem::DELIMITER . 'vor @duration @units',
    ])->save();
  }

  /**
   * Cover the \Twig_Extensions_Extension_Date::diff.
   */
  public function testDateDiffTimeAgoAuto() {
    $this->drupalGet('/fr/bamboo-twig-extensions');
    $this->assertElementContains('.test-extensions div.date-diff-ago-1', 'il y a 1 seconde');
    $this->assertElementContains('.test-extensions div.date-diff-ago-2', 'il y a 5 secondes');
    $this->assertElementContains('.test-extensions div.date-diff-ago-3', 'il y a 1 minute');
    $this->assertElementContains('.test-extensions div.date-diff-ago-4', 'il y a 5 minutes');
    $this->assertElementContains('.test-extensions div.date-diff-ago-5', 'il y a 1 heure');
    $this->assertElementContains('.test-extensions div.date-diff-ago-6', 'il y a 9 heures');
    $this->assertElementContains('.test-extensions div.date-diff-ago-7', 'il y a 1 jour');
    $this->assertElementContains('.test-extensions div.date-diff-ago-8', 'il y a 4 jours');
    $this->assertElementContains('.test-extensions div.date-diff-ago-10', 'il y a 1 mois');
    $this->assertElementContains('.test-extensions div.date-diff-ago-11', 'il y a 5 mois');
    $this->assertElementContains('.test-extensions div.date-diff-ago-12', 'il y a 1 an');
    $this->assertElementContains('.test-extensions div.date-diff-ago-13', 'il y a 3 ans');

    $this->drupalGet('/de/bamboo-twig-extensions');
    $this->assertElementContains('.test-extensions div.date-diff-ago-1', 'vor 1 Sekunde');
    $this->assertElementContains('.test-extensions div.date-diff-ago-2', 'vor 5 Sekunden');
    $this->assertElementContains('.test-extensions div.date-diff-ago-3', 'vor 1 Minute');
    $this->assertElementContains('.test-extensions div.date-diff-ago-4', 'vor 5 Minuten');
    $this->assertElementContains('.test-extensions div.date-diff-ago-5', 'vor 1 Stunde');
    $this->assertElementContains('.test-extensions div.date-diff-ago-6', 'vor 9 Stunden');
    $this->assertElementContains('.test-extensions div.date-diff-ago-7', 'vor 1 Tag');
    $this->assertElementContains('.test-extensions div.date-diff-ago-8', 'vor 4 Tagen');
    $this->assertElementContains('.test-extensions div.date-diff-ago-10', 'vor 1 Monat');
    $this->assertElementContains('.test-extensions div.date-diff-ago-11', 'vor 5 Monaten');
    $this->assertElementContains('.test-extensions div.date-diff-ago-12', 'vor 1 Jahr');
    $this->assertElementContains('.test-extensions div.date-diff-ago-13', 'vor 3 Jahren');
  }

  /**
   * Cover the \Twig_Extensions_Extension_Date::diff.
   */
  public function testDateDiffTimeInAuto() {
    $this->drupalGet('/fr/bamboo-twig-extensions');
    $this->assertElementContains('.test-extensions div.date-diff-in-1', 'dans 1 second');
    $this->assertElementContains('.test-extensions div.date-diff-in-2', 'dans 5 secondes');
    $this->assertElementContains('.test-extensions div.date-diff-in-3', 'dans 1 minute');
    $this->assertElementContains('.test-extensions div.date-diff-in-4', 'dans 5 minutes');
    $this->assertElementContains('.test-extensions div.date-diff-in-5', 'dans 1 heure');
    $this->assertElementContains('.test-extensions div.date-diff-in-6', 'dans 9 heures');
    $this->assertElementContains('.test-extensions div.date-diff-in-7', 'dans 1 jour');
    $this->assertElementContains('.test-extensions div.date-diff-in-8', 'dans 5 jours');
    $this->assertElementContains('.test-extensions div.date-diff-in-9', 'dans 1 mois');
    $this->assertElementContains('.test-extensions div.date-diff-in-10', 'dans 6 mois');
    $this->assertElementContains('.test-extensions div.date-diff-in-11', 'dans 1 an');
    $this->assertElementContains('.test-extensions div.date-diff-in-12', 'dans 3 ans');

    $this->drupalGet('/de/bamboo-twig-extensions');
    $this->assertElementContains('.test-extensions div.date-diff-in-1', 'in 1 Sekunde');
    $this->assertElementContains('.test-extensions div.date-diff-in-2', 'in 5 Sekunden');
    $this->assertElementContains('.test-extensions div.date-diff-in-3', 'in 1 Minute');
    $this->assertElementContains('.test-extensions div.date-diff-in-4', 'in 5 Minuten');
    $this->assertElementContains('.test-extensions div.date-diff-in-5', 'in 1 Stunde');
    $this->assertElementContains('.test-extensions div.date-diff-in-6', 'in 9 Stunden');
    $this->assertElementContains('.test-extensions div.date-diff-in-7', 'in 1 Tag');
    $this->assertElementContains('.test-extensions div.date-diff-in-8', 'in 5 Tagen');
    $this->assertElementContains('.test-extensions div.date-diff-in-9', 'in 1 Monat');
    $this->assertElementContains('.test-extensions div.date-diff-in-10', 'in 6 Monaten');
    $this->assertElementContains('.test-extensions div.date-diff-in-11', 'in 1 Jahr');
    $this->assertElementContains('.test-extensions div.date-diff-in-12', 'in 3 Jahren');
  }

}
