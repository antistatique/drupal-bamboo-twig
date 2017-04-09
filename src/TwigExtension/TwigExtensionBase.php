<?php

namespace Drupal\bamboo_twig\TwigExtension;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Provides a Twig Extension Lazy Service Injection.
 */
class TwigExtensionBase extends \Twig_Extension {
  use ContainerAwareTrait;

  /**
   * Lazy loading for the Drupal entity type manager.
   *
   * @return \Drupal\Core\Entity\EntityTypeManagerInterface
   *   Return the Drupal entity type manager.
   */
  protected function getEntityTypeManager() {
    return $this->container->get('entity_type.manager');
  }

  /**
   * Return the current route match.
   *
   * @return \Drupal\Core\Routing\RouteMatchInterface
   *   Return the current route match.
   */
  protected function getCurrentRouteMatch() {
    return $this->container->get('current_route_match');
  }

  /**
   * Manages discovery and instantiation of block plugins.
   *
   * @return \Drupal\Core\Block\BlockManagerInterface
   *   Return the block manager.
   */
  protected function getPluginManagerBlock() {
    return $this->container->get('plugin.manager.block');
  }

  /**
   * Provides an interface for form building and processing.
   *
   * @return \Drupal\Core\Form\FormBuilderInterface
   *   Return the interface for form building and processing.
   */
  protected function getFormBuilder() {
    return $this->container->get('form_builder');
  }

  /**
   * Interface for loading, transforming and rendering menu link trees.
   *
   * @return \Drupal\Core\Menu\MenuLinkTreeInterface
   *   Return the interface for loading, transforming and rendering menu link.
   */
  protected function getMenuLinkTree() {
    return $this->container->get('menu.link_tree');
  }

  /**
   * Provides an interface for a configuration object factory.
   *
   * @return \Drupal\Core\Config\ConfigFactoryInterface
   *   Return the interface for a configuration object factory.
   */
  protected function getConfigFactory() {
    return $this->container->get('config.factory');
  }

  /**
   * Lazy loading for the Drupal current user account proxy.
   *
   * @return \Drupal\Core\Session\AccountInterface
   *   Return The current user account proxy.
   */
  protected function getCurrentUser() {
    return $this->container->get('current_user');
  }

  /**
   * Return the user storage.
   *
   * @return \Drupal\user\UserStorageInterface
   *   Return the user storage.
   */
  protected function getUserStorage() {
    return $this->getEntityTypeManager()->getStorage('user');
  }

  /**
   * Return the block storage.
   *
   * @return \Drupal\user\UserStorageInterface
   *   Return the block storage.
   */
  protected function getBlockStorage() {
    return $this->getEntityTypeManager()->getStorage('block');
  }

  /**
   * Return the file storage.
   *
   * @return \Drupal\Component\PhpStorage\FileStorage
   *   Return the file storage.
   */
  protected function getFileStorage() {
    return $this->getEntityTypeManager()->getStorage('file');
  }

  /**
   * Provides an interface defining an image style.
   *
   * @return \Drupal\image\ImageStyleInterface
   *   Return interface for image style.
   */
  protected function getImageStyleStorage() {
    return $this->getEntityTypeManager()->getStorage('image_style');
  }

  /**
   * Return the factory for image objects.
   *
   * @return \Drupal\Core\Image\ImageFactory
   *   Return the factory for image objects.
   */
  protected function getImageFactory() {
    return $this->container->get('image.factory');
  }

  /**
   * Return the factory for image objects.
   *
   * @return \Drupal\Core\Field\FieldTypePluginManager
   *   Return the factory for image objects.
   */
  protected function getFieldTypeManager() {
    return $this->container->get('plugin.manager.field.field_type');
  }

  /**
   * Return the token service.
   *
   * @return \Drupal\Core\Utility\Token
   *   Return the token service.
   */
  protected function getToken() {
    return $this->container->get('token');
  }

}
