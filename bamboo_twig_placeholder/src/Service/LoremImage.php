<?php

namespace Drupal\bamboo_twig_placeholder\Service;

use Drupal\Core\File\FileSystem;

/**
 * LoremImage Generator Class.
 *
 * Creates temporary placeholder images.
 */
class LoremImage {
  /**
   * Provides helpers to operate on files and stream wrappers.
   *
   * @var \Drupal\Core\File\FileSystem
   */
  protected $fso;

  /**
   * Get the private absolute folder path.
   */
  private $directory = 'public://placeholders';

  private $background = 'dddddd';
  private $color = '000000';
  private $label;

  private $font;

  private $width;
  private $height;

  /**
   *
   */
  public function __construct(FileSystem $fso) {
    $this->fso = $fso;
    $this->font = drupal_get_path('module', 'bamboo_twig_placeholder') . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'Oswald-Regular.ttf';
  }

  /**
   * Sets location of TTF font.
   *
   * @param string $fontPath
   *   Location of TTF font.
   *
   * @throws \InvalidArgumentException
   */
  public function setFont($fontPath) {
    if (is_readable($fontPath)) {
      $this->font = $fontPath;
    }
    else {
      throw new \InvalidArgumentException('Font file must exist and be readable by web server.');
    }
  }

  /**
   * Display image.
   *
   * @throws RuntimeException
   */
  public function render($width, $height, $background = NULL, $color = NULL, $label = FALSE, $override = FALSE) {
    $this->width      = $width;
    $this->height     = $height;
    $this->background = $this->fromStringToHex($background);
    $this->color      = $this->fromStringToHex($color);

    $this->label = $label;
    if (empty($label)) {
      $this->label = $width . 'x' . $height;
    }

    $file = $this->getFileFullPath();
    if (!is_readable($file) || $override) {
      $file = $this->createImage($file);
    }

    dump($file);

    if (is_readable($file)) {
      return file_create_url($file);
    }

    return NULL;
  }

  /**
   *
   */
  private function getFileFullPath() {
    file_prepare_directory($this->directory, FILE_CREATE_DIRECTORY);
    file_prepare_directory($this->directory, FILE_MODIFY_PERMISSIONS);

    return $this->directory . DIRECTORY_SEPARATOR . $this->width . '_' . $this->height . '_' . (strlen($this->background) === 3 ? $this->background[0] . $this->background[0] . $this->background[1] . $this->background[1] . $this->background[2] . $this->background[2] : $this->background) . '_' . (strlen($this->color) === 3 ? $this->color[0] . $this->color[0] . $this->color[1] . $this->color[1] . $this->color[2] . $this->color[2] : $this->color) . '.png';
  }

  /**
   *
   */
  private function createImage($file) {
    if (is_file($file)) {
      unlink($file);
    }

    $image = imagecreate($this->width, $this->height);
    // Convert backgroundColor hex to RGB values.
    list($bgR, $bgG, $bgB) = $this->hexToDec($this->background);
    $backgroundColor = imagecolorallocate($image, $bgR, $bgG, $bgB);
    // Convert textColor hex to RGB values.
    list($textR, $textG, $textB) = $this->hexToDec($this->color);
    $textColor = imagecolorallocate($image, $textR, $textG, $textB);

    // $text = $this->width . 'x' . $this->height;.
    imagefilledrectangle($image, 0, 0, $this->width, $this->height, $backgroundColor);
    $fontSize = 26;
    $textBoundingBox = imagettfbbox($fontSize, 0, $this->font, $this->label);
    // Decrease the default font size until it fits nicely within the image.
    while (((($this->width - ($textBoundingBox[2] - $textBoundingBox[0])) < 10) || (($this->height - ($textBoundingBox[1] - $textBoundingBox[7])) < 10)) && ($fontSize > 1)) {
      $fontSize--;
      $textBoundingBox = imagettfbbox($fontSize, 0, $this->font, $this->label);
    }
    imagettftext($image, $fontSize, 0, ($this->width / 2) - (($textBoundingBox[2] - $textBoundingBox[0]) / 2), ($this->height / 2) + (($textBoundingBox[1] - $textBoundingBox[7]) / 2), $textColor, $this->font, $this->label);

    imagepng($image, $file);
    return $file;
  }

  /**
   * Convert hex code to array of RGB decimal values.
   *
   * @param string $hex
   *   Hex code to convert to dec.
   *
   * @return array
   *
   * @throws \InvalidArgumentException
   */
  private function hexToDec($hex) {
    if (strlen($hex) === 3) {
      $rgbArray = [hexdec($hex[0] . $hex[0]), hexdec($hex[1] . $hex[1]), hexdec($hex[2] . $hex[2])];
    }
    elseif (strlen($hex) === 6) {
      $rgbArray = [hexdec($hex[0] . $hex[1]), hexdec($hex[2] . $hex[3]), hexdec($hex[4] . $hex[5])];
    }
    else {
      throw new \InvalidArgumentException('Could not convert hex value to decimal.');
    }
    return $rgbArray;
  }

  /**
   * Sets text color.
   *
   * @param string $hex
   *   Hex code value.
   *
   * @throws \InvalidArgumentException
   */
  public function fromStringToHex($hex) {
    if (strlen($hex) === 3 || strlen($hex) === 6) {
      if (preg_match('/^[a-f0-9]{3}$|^[a-f0-9]{6}$/i', $hex)) {
        return $hex;
      }
      else {
        throw new \InvalidArgumentException('Text color must be a valid RGB hex code.');
      }
    }
    else {
      throw new \InvalidArgumentException('Text color must be 3 or 6 character hex code.');
    }
  }

}
