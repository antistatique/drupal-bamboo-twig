<?php

namespace Drupal\Tests\bamboo_twig\Traits;

use Drupal\block_content\Entity\BlockContent;
use Drupal\block_content\Entity\BlockContentType;

/**
 * Provides methods to create and place block with default settings.
 *
 * This trait is meant to be used only by test classes.
 */
trait BlockCreationTrait {

  /**
   * Create a custom Block Content type.
   *
   * @param string $type
   *   The block type to create or load.
   * @param string|null $label
   *   (optional) The label to attach with the created block content type.
   *
   * @return \Drupal\block_content\Entity\BlockContentType
   *   The created block content type.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function createBlockContentType(string $type, ?string $label = NULL): BlockContentType {
    $block_content_type = BlockContentType::load($type);
    if (!$block_content_type) {
      $block_content_type = BlockContentType::create([
        'id' => $type,
        'label' => $label ?? $this->randomString(),
        'revision' => FALSE,
      ]);
      $block_content_type->save();
    }

    return $block_content_type;
  }

  /**
   * Creates a custom block.
   *
   * @param bool|string $title
   *   (optional) Title of block. When no value is given uses a random name.
   *   Defaults to FALSE.
   * @param string $bundle
   *   (optional) Bundle name. Defaults to 'basic'.
   * @param bool $save
   *   (optional) Whether to save the block. Defaults to TRUE.
   *
   * @return \Drupal\block_content\Entity\BlockContent
   *   Created custom block.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function createBlockContent($title = FALSE, $bundle = 'basic', $save = TRUE): BlockContent {
    $title = $title ?: $this->randomMachineName();
    $block_content = BlockContent::create([
      'info' => $title,
      'type' => $bundle,
      'langcode' => 'en',
    ]);
    if ($block_content && $save === TRUE) {
      $block_content->save();
    }
    return $block_content;
  }

}
