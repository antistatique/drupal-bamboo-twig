<?php

namespace Drupal\Tests\bamboo_twig\Traits;

use Drupal\block_content\Entity\BlockContent;
use Drupal\block_content\Entity\BlockContentType;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;

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
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function createBlockContentType(string $type, ?string $label = NULL): BlockContentType {
    if (!$block_content_type = BlockContentType::load($type)) {
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

  /**
   * Adds the default body field to a custom content block type.
   *
   * @param \Drupal\block_content\Entity\BlockContentType $block_content_type
   *   The Block type to add body on.
   * @param string $label
   *   (optional) The label for the body instance. Defaults to 'Body'.
   *
   * @return \Drupal\field\Entity\FieldConfig
   *   A Body field object.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function addBodyBlockContent(BlockContentType $block_content_type, string $label = 'Body'): FieldConfig {
    $block_type_id = $block_content_type->id();

    // Add or remove the body field, as needed.
    $field = FieldConfig::loadByName('block_content', $block_type_id, 'body');

    if ($field !== NULL) {
      return $field;
    }

    $field = FieldConfig::create([
      // The field storage is guaranteed to exist because it is supplied by the
      // block_content module.
      'field_storage' => FieldStorageConfig::loadByName('block_content', 'body'),
      'bundle' => $block_type_id,
      'label' => $label,
      'settings' => ['display_summary' => FALSE],
    ]);
    $field->save();

    return $field;
  }

}
