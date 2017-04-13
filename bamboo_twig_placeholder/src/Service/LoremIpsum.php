<?php

namespace Drupal\bamboo_twig_placeholder\Service;

/**
 * LoremIpsum Generator Class.
 */
class LoremIpsum {
  /**
   * List of words used for generate the lorem ipsum value.
   *
   * @var array
   */
  private $words = [
    'a', 'ac', 'adipiscing', 'aenean', 'aliquam', 'aliquet', 'amet', 'ante', 'arcu', 'at',
    'augue', 'bibendum', 'blandit', 'congue', 'consectetuer', 'consectetur', 'consequat', 'convallis', 'cras', 'cubilia',
    'curabitur', 'curae', 'cursus', 'diam', 'dignissim', 'dolor', 'dui', 'duis', 'egestas', 'eleifend',
    'elementum', 'elit', 'enim', 'erat', 'eros', 'est', 'et', 'eu', 'euismod', 'faucibus',
    'felis', 'fermentum', 'feugiat', 'fusce', 'gravida', 'hendrerit', 'iaculis', 'id', 'imperdiet', 'in',
    'integer', 'ipsum', 'justo', 'lacus', 'lectus', 'leo', 'libero', 'ligula', 'lobortis', 'lorem',
    'luctus', 'maecenas', 'magna', 'massa', 'mauris', 'metus', 'mi', 'molestie', 'mollis', 'morbi',
    'nec', 'nibh', 'nisl', 'non', 'nonummy', 'nulla', 'nunc', 'odio', 'orci', 'ornare',
    'pede', 'pellentesque', 'pharetra', 'placerat', 'porttitor', 'posuere', 'praesent', 'pretium', 'primis', 'proin',
    'pulvinar', 'purus', 'quam', 'quis', 'rhoncus', 'risus', 'rutrum', 'sapien', 'scelerisque', 'sed',
    'sem', 'semper', 'sit', 'sodales', 'sollicitudin', 'suscipit', 'suspendisse', 'tellus', 'tempor', 'tempus',
    'tincidunt', 'tortor', 'tristique', 'turpis', 'ullamcorper', 'ultrices', 'ultricies', 'ut', 'varius', 'vehicula',
    'vel', 'velit', 'vestibulum', 'vitae', 'vivamus', 'volutpat', 'vulputate',
  ];

  /**
   * Punctuation of the sentence.
   *
   * @var array
   */
  private $punctuation = [',', ';', ':'];

  /**
   * Different characters for finish the sentence.
   *
   * @var array
   */
  private $end = ['?', '!', '...', '.'];

  /**
   * The last word used during the phrase generate.
   *
   * @var string
   */
  private $lastWord = NULL;

  /**
   * The minimum number of paragraphs.
   *
   * @var int
   */
  private $paragraphsMin;

  /**
   * The maximum number of paragraphs.
   *
   * @var int
   */
  private $paragraphsMax;

  /**
   * The minimum number of sentences in a paragraph.
   *
   * @var int
   */
  private $sentencesMin;

  /**
   * The maximum number of sentences in a paragraph.
   *
   * @var int
   */
  private $sentencesMax;

  /**
   * The minimum number of words in a sentence.
   *
   * @var int
   */
  private $wordsMin;

  /**
   * The maximum number of words in a sentence.
   *
   * @var int
   */
  private $wordsMax;

  /**
   * The percentage of punctuation in a sentence.
   *
   * @var int
   */
  private $quantityPunctuation;

  /**
   * Constructor of the LoremIpsum class.
   *
   * @param int $paragraphsMin
   *   The minimum number of paragraphs.
   * @param int $paragraphsMax
   *   The maximum number of paragraphs.
   * @param int $sentencesMin
   *   The minimum number of sentences in a paragraph.
   * @param int $sentencesMax
   *   The maximum number of sentences in a paragraph.
   * @param int $wordsMin
   *   The minimum number of words in a sentence.
   * @param int $wordsMax
   *   The maximum number of words in a sentence.
   * @param int $quantityPunctuation
   *   The percentage of punctuation in a sentence.
   * @param string $loremIpsum
   *   Minimum number of words in a sentence.
   */
  public function __construct($paragraphsMin = 1,
  $paragraphsMax = 100,
  $sentencesMin = 1,
  $sentencesMax = 100,
  $wordsMin = 1,
  $wordsMax = 100,
  $quantityPunctuation = 10,
  $loremIpsum = NULL) {
    // Set the paragraphs parameters.
    $this->setParagraphsMin($paragraphsMin);
    $this->setParagraphsMax($paragraphsMax);

    // Set the sentences parameters.
    $this->setSentencesMin($sentencesMin);
    $this->setSentencesMax($sentencesMax);

    // Set the words parameters.
    $this->setWordsMin($wordsMin);
    $this->setWordsMax($wordsMax);

    // Set the quantity of punctuation parameter.
    $this->setQuantityPunctuation($quantityPunctuation);

    // Set the words for lorem ipsum value.
    if ($loremIpsum !== NULL) {
      $this->setLoremIpsum($loremIpsum);
    }
  }

  /**
   * Set the minimum number of paragraphs.
   *
   * @param int $min
   *   The minimum number of paragraphs.
   *
   * @return LoremIpsum
   *   The LoremIpsum Generator Class.
   */
  public function setParagraphsMin($min) {
    if (is_int($min) && $min > 0) {
      $this->paragraphsMin = $min;
    }
    else {
      throw new \InvalidArgumentException('The value of $min must be a absolute int.');
    }

    return $this;
  }

  /**
   * Set the maximum number of paragraphs.
   *
   * @param int $max
   *   The maximum number of paragraphs.
   *
   * @return LoremIpsum
   *   The LoremIpsum Generator Class.
   */
  public function setParagraphsMax($max) {
    if (is_int($max) && $max > 0) {
      $this->paragraphsMax = $max;
    }
    else {
      throw new \InvalidArgumentException('The value of $max must be a absolute int.');
    }

    return $this;
  }

  /**
   * Set the minimum number of sentences in a paragraph.
   *
   * @param int $min
   *   The minimum number of sentences in a paragraph.
   *
   * @return LoremIpsum
   *   The LoremIpsum Generator Class.
   */
  public function setSentencesMin($min) {
    if (is_int($min) && $min > 0) {
      $this->sentencesMin = $min;
    }
    else {
      throw new \InvalidArgumentException('The value of $min must be a absolute int.');
    }

    return $this;
  }

  /**
   * Set the maximum number of sentences in a paragraph.
   *
   * @param int $max
   *   The maximum number of sentences in a paragraph.
   *
   * @return LoremIpsum
   *   The LoremIpsum Generator Class.
   */
  public function setSentencesMax($max) {
    if (is_int($max) && $max > 0) {
      $this->sentencesMax = $max;
    }
    else {
      throw new \InvalidArgumentException('The value of $max must be a absolute int.');
    }

    return $this;
  }

  /**
   * Set the minimum number of words in a sentence.
   *
   * @param int $min
   *   The minimum number of words in a sentence.
   *
   * @return LoremIpsum
   *   The LoremIpsum Generator Class.
   */
  public function setWordsMin($min) {
    if (is_int($min) && $min > 0) {
      $this->wordsMin = $min;
    }
    else {
      throw new \InvalidArgumentException('The value of $min must be a absolute int.');
    }

    return $this;
  }

  /**
   * Set the maximum number of words in a sentence.
   *
   * @param int $max
   *   The maximum number of words in a sentence.
   *
   * @return LoremIpsum
   *   The LoremIpsum Generator Class.
   */
  public function setWordsMax($max) {
    if (is_int($max) && $max > 0) {
      $this->wordsMax = $max;
    }
    else {
      throw new \InvalidArgumentException('The value of $max must be a absolute int.');
    }

    return $this;
  }

  /**
   * Set the percentage quantity of punctuation.
   *
   * @param int $qty
   *   The percentage quantity of punctuation.
   *
   * @return LoremIpsum
   *   The LoremIpsum Generator Class.
   */
  public function setQuantityPunctuation($qty) {
    if (is_int($qty) && $qty >= 0 && $qty <= 100) {
      $this->quantityPunctuation = $qty;
    }
    else {
      throw new \InvalidArgumentException('The $qty must be a percentage');
    }

    return $this;
  }

  /**
   * Set the minimum number of words in a sentence.
   *
   * @param string $txt
   *   The minimum number of words in a sentence.
   *
   * @return LoremIpsum
   *   The LoremIpsum Generator Class.
   */
  public function setLoremIpsum($txt) {
    if (mb_detect_encoding($txt) == 'ISO-8859-1') {
      $txt = utf8_encode($txt);
    }

    $txt = mb_strtolower($txt, 'UTF-8');

    $txt = preg_replace('#[^a-zA-Zàáâãäåæœçèéêëìíîïðòóôõöùúûüñýÿ\' -]|«|»#iu', ' ', $txt);

    if (empty($txt)) {
      throw new \InvalidArgumentException('The LoremIpsum value must be a string.');
    }
    $txt = preg_replace('# {2,}#', ' ', $txt);
    $words = explode(' ', $txt);
    $this->words = [];
    foreach ($words as $n) {
      if (!in_array($n, $this->words) && preg_match('#[a-z]#', $n)) {
        $this->words[] = $n;
      }
    }

    return $this;
  }

  /**
   * Get a text with aleatory number of paragraphs.
   *
   * Use the default configuration for generate the text.
   * If you use only one parameter, the function return the exactely paragraphs
   * number of this parameter.
   * If you use two parameters, the function return aleatory paragraphs
   * into the first and second parameter.
   *
   * @param int $nbrOrMin
   *   The exactely number of paragraphs or the minimum.
   * @param int $max
   *   The maximum of paragraphs.
   *
   * @return string
   *   A sequences of text.
   */
  public function getParagraphs($nbrOrMin = NULL, $max = NULL) {
    if ($nbrOrMin != NULL && is_int($nbrOrMin) && $max != NULL && is_int($max)) {
      if ($nbrOrMin > $max) {
        throw new \InvalidArgumentException('The value of $nbrOrMin must be smaller than $max');
      }

      $nbrParagraph = mt_rand($nbrOrMin, $max);
    }
    elseif ($nbrOrMin != NULL && is_int($nbrOrMin) && $max == NULL) {
      $nbrParagraph = $nbrOrMin;
    }
    elseif ($nbrOrMin != NULL || $max != NULL) {
      throw new \InvalidArgumentException('The $nbrOrMin and $max must be a number value');
    }
    else {
      $nbrParagraph = mt_rand($this->paragraphsMin, $this->paragraphsMax);
    }

    $txt = '';
    for ($i = 0; $i < $nbrParagraph; $i++) {
      $txt .= $this->generateParagraph();
    }

    return $txt;
  }

  /**
   * Get a paragraph with aleatory number of sentences.
   *
   * Use the default configuration for generate the paragraph.
   * If you use only one parameter, the function return the exactely sentences
   * number of this parameter.
   * If you use two parameters, the function return aleatory sentences
   * into the first and second parameter.
   *
   * @param int $nbrOrMin
   *   The exactely number of sentences or the minimum.
   * @param int $max
   *   The maximum of sentences.
   *
   * @return string
   *   A sequences of aleatory number.
   */
  public function getSentences($nbrOrMin = NULL, $max = NULL) {
    if ($nbrOrMin != NULL && is_int($nbrOrMin) && $max != NULL && is_int($max)) {
      if ($nbrOrMin > $max) {
        throw new \InvalidArgumentException('The value of $nbrOrMin must be smaller than $max');
      }

      $nbrSentences = mt_rand($nbrOrMin, $max);
    }
    elseif ($nbrOrMin != NULL && is_int($nbrOrMin) && $max == NULL) {
      $nbrSentences = $nbrOrMin;
    }
    elseif ($nbrOrMin != NULL || $max != NULL) {
      throw new \InvalidArgumentException('The $nbrOrMin and $max must be a number value');
    }
    else {
      $nbrSentences = mt_rand($this->sentenceMin, $this->sentencesMax);
    }

    $txt = '';
    for ($i = 0; $i < $nbrSentences; $i++) {
      if ($i != 0) {
        $txt .= ' ';
      }
      $txt .= $this->generateSentence();
    }

    return $txt;
  }

  /**
   * Get a sequences of aleatory number of words.
   *
   * Use the default configuration for generate the sentence.
   * If you use only one parameter, the function return the exactely words
   * number of this parameter.
   * If you use two parameters, the function return aleatory words
   * into the first and second parameter.
   *
   * @param int $nbrOrMin
   *   The exactely number of words or the minimum.
   * @param int $max
   *   The maximum of words.
   *
   * @return string
   *   This is not a sentence because it does not have
   *   a capital and punctuation.
   */
  public function getWords($nbrOrMin = NULL, $max = NULL) {
    if ($nbrOrMin != NULL && is_int($nbrOrMin) && $max != NULL && is_int($max)) {
      if ($nbrOrMin > $max) {
        throw new \InvalidArgumentException('The value of $nbrOrMin must be smaller than $max');
      }

      $nbrWords = mt_rand($nbrOrMin, $max);
    }
    elseif ($nbrOrMin != NULL && is_int($nbrOrMin) && $max == NULL) {
      $nbrWords = $nbrOrMin;
    }
    elseif ($nbrOrMin != NULL || $max != NULL) {
      throw new \InvalidArgumentException('The $nbrOrMin and $max must be a number value');
    }
    else {
      $nbrWords = mt_rand($this->wordsMin, $this->wordsMax);
    }

    $txt = '';
    for ($i = 0; $i < $nbrWords; $i++) {
      if ($i != 0) {
        $txt .= ' ';
      }

      $txt .= $this->generateWord();
    }

    return $txt;
  }

  /**
   * Get the words of Lorem Ipsum.
   *
   * @return array
   *   The list of words to use when generating Lorem Ipsum.
   */
  public function getLoremIpsumArray() {
    return $this->words;
  }

  /**
   * Generate an aleatory paragraph.
   *
   * @return string
   *   Return string with <p> of html tag.
   */
  private function generateParagraph() {
    $nbrSentences = mt_rand($this->sentencesMin, $this->sentencesMax);

    $paragraph = '';
    for ($i = 0; $i < $nbrSentences; $i++) {
      if ($i != 0) {
        $paragraph .= ' ';
      }

      $paragraph .= $this->generateSentence();
    }

    return '<p>' . $paragraph . '</p>';
  }

  /**
   * Generate an aleatory sentence.
   *
   * @return string
   *   An aleatory sentence.
   */
  private function generateSentence($nbrWords = NULL) {
    if ($nbrWords == NULL) {
      $nbrWords = mt_rand($this->wordsMin, $this->wordsMax);
    }

    $sentence = '';
    for ($i = 0; $i < $nbrWords; $i++) {
      if ($i != 0) {
        if (mt_rand(0, 100) <= $this->quantityPunctuation) {
          $sentence .= $this->generatePunctuation();
        }

        $sentence .= ' ';
      }

      $sentence .= $this->generateWord();
    }

    if (mt_rand(0, 1) == 1) {
      $sentence .= $this->generateEnd();
    }
    else {
      $sentence .= '.';
    }

    return ucfirst($sentence);
  }

  /**
   * Get an aleatory word.
   *
   * @return string
   *   An aleatory word.
   */
  private function generateWord() {
    do {
      $key = array_rand($this->words, 1);

    } while ($key == $this->lastWord);

    $this->lastWord = $key;

    return $this->words[$key];
  }

  /**
   * Get an aleatory punctuation.
   *
   * @return string
   *   An aleatory punctuation.
   */
  private function generatePunctuation() {
    if (mt_rand(0, 4) == 4) {
      return $this->punctuation[array_rand($this->punctuation, 1)];
    }

    return ',';
  }

  /**
   * Get an aleatory end of sentence.
   *
   * @return string
   *   An aleatory sentence.
   */
  private function generateEnd() {
    if (mt_rand(0, 2) == 2) {
      return $this->end[array_rand($this->end, 1)];
    }

    return '.';
  }

}
