<?php

namespace Drupal\bamboo_twig_loader\TwigExtension;

use Drupal\bamboo_twig\TwigExtension\TwigExtensionBase;

/**
 * Provides some loaders as Twig Extensions.
 */
class Loader extends TwigExtensionBase {

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('bamboo_load_entity', [$this, 'loadEntity']),
      new \Twig_SimpleFunction('bamboo_load_currentuser', [$this, 'loadCurrentUser']),
      new \Twig_SimpleFunction('bamboo_load_image', [$this, 'loadImage']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig_loader.twig.loader';
  }

  /**
   * Returns an entity object.
   *
   * @param string $entity_type
   *   The entity type.
   * @param mixed $id
   *   (optional) The ID of the entity to load.
   *
   * @return null|Drupal\Core\Entity\EntityInterface
   *   An entity object for the entity or NULL if the entity does not exist.
   */
  public function loadEntity($entity_type, $id = NULL) {
    // Lazy load the entity type manager only when needed.
    $entityTypeManager = $this->getEntityTypeManager();

    $entity = $id ?
      $entityTypeManager->getStorage($entity_type)->load($id) :
      $this->getCurrentRouteMatch()->getParameter($entity_type);

    if (!$entity) {
      return NULL;
    }

    return $entity;
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
