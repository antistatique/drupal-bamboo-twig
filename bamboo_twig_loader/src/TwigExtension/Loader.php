<?php

namespace Drupal\bamboo_twig_loader\TwigExtension;

use Drupal\bamboo_twig\TwigExtension\TwigExtensionBase;
use Drupal\Core\Entity\EntityInterface;
use Twig\TwigFunction;

/**
 * Provides some loaders as Twig Extensions.
 */
class Loader extends TwigExtensionBase {

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new TwigFunction('bamboo_load_entity', [$this, 'loadEntity']),
      new TwigFunction('bamboo_load_entity_revision', [
        $this, 'loadEntityRevision',
      ]),
      new TwigFunction('bamboo_load_field', [$this, 'loadField']),
      new TwigFunction('bamboo_load_currentuser', [$this, 'loadCurrentUser']),
      new TwigFunction('bamboo_load_image', [$this, 'loadImage']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig_loader.twig.loader';
  }

  /**
   * Returns an entity object in the current context language.
   *
   * Keep in mind languages loading priorities:
   *  1. Get the entity in the current page lang,
   *  2. When not found, try to fetch the entity in the default site lang,
   *  3. When not found in 2 previous attempts, fetch the original entity lang.
   *
   * @param string $entity_type
   *   The entity type.
   * @param mixed $id
   *   (optional) The ID of the entity to load.
   * @param string $langcode
   *   (optional) For which language the entity should be rendered, defaults to
   *   the current content language.
   *
   * @return null|Drupal\Core\Entity\EntityInterface
   *   An entity object for the entity or NULL if the entity does not exist.
   */
  public function loadEntity($entity_type, $id = NULL, $langcode = NULL) {
    $entityRepository = $this->getEntityRepository();

    $entity = $id ?
      $this->getEntityTypeManager()->getStorage($entity_type)->load($id) :
      $this->getCurrentRouteMatch()->getParameter($entity_type);

    if (!$entity instanceof EntityInterface) {
      return NULL;
    }

    // Get the entity in the current context language.
    return $entityRepository->getTranslationFromContext($entity, $langcode);
  }

  /**
   * Returns an entity revision object in the current context language.
   *
   * Keep in mind languages loading priorities:
   *  1. Get the entity in the current page lang,
   *  2. When not found, try to fetch the entity in the default site lang,
   *  3. When not found in 2 previous attempts, fetch the original entity lang.
   *
   * @param string $entity_type
   *   The entity type.
   * @param mixed $revision_id
   *   (optional) The revision ID of the entity to be loaded.
   * @param string $langcode
   *   (optional) For which language the entity should be rendered, defaults to
   *   the current content language.
   *
   * @return null|\Drupal\Core\Entity\EntityInterface
   *   An entity revision object or NULL if the revision does not exist.
   */
  public function loadEntityRevision($entity_type, $revision_id = NULL, $langcode = NULL) {
    $entityRepository = $this->getEntityRepository();

    $revision = $revision_id ?
      $this->getEntityTypeManager()->getStorage($entity_type)->loadRevision($revision_id) :
      $this->getCurrentRouteMatch()->getParameter($entity_type . '_revision');

    if (!$revision instanceof EntityInterface) {
      return NULL;
    }

    // Get the entity in the current context language.
    return $entityRepository->getTranslationFromContext($revision, $langcode);
  }

  /**
   * Returns the field object for a single entity field.
   *
   * @param string $field_name
   *   The field name.
   * @param string $entity_type
   *   The entity type.
   * @param mixed $id
   *   (optional) The ID of the entity to render.
   * @param string $langcode
   *   (optional) Language code to load translation.
   *
   * @return null|Drupal\Core\Field\FieldItemListInterface
   *   A field object for the entity or NULL if the value does not exist.
   */
  public function loadField($field_name, $entity_type, $id = NULL, $langcode = NULL) {
    // Load the entity view using the current content language.
    if (!$langcode) {
      $langcode = $this->getLanguageManager()->getCurrentLanguage()->getId();
    }

    $entity = $this->loadEntity($entity_type, $id, $langcode);

    if (!$entity) {
      return NULL;
    }

    // Ensure the entity has the requested field.
    if (!$entity->hasField($field_name)) {
      return NULL;
    }

    // Do not continue if the field is empty.
    if ($entity->get($field_name)->isEmpty()) {
      return NULL;
    }

    if (isset($entity->{$field_name})) {
      return $entity->{$field_name};
    }

    return NULL;
  }

  /**
   * Return the current user object.
   *
   * @return \Drupal\user\Entity\User|null
   *   The current user object or NULL when anonymous.
   */
  public function loadCurrentUser() {
    $currentUser = $this->getCurrentUser();

    if ($currentUser->isAnonymous()) {
      return NULL;
    }

    return $this->getUserStorage()->load($currentUser->id());
  }

  /**
   * Load a ImageInterface object for an original image path or URI.
   *
   * @param string $path
   *   The path or URI to the original image.
   *
   * @return \Drupal\Core\Image\ImageInterface
   *   An Image object.
   */
  public function loadImage($path) {
    return $this->getImageFactory()->get($path);
  }

}
