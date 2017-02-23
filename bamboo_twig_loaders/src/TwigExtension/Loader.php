<?php

namespace Drupal\bamboo_twig_loaders\TwigExtension;

use Drupal\file\Plugin\Field\FieldType\FileFieldItemList;

// Injection.
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;

/**
 * Provides a 'Loader' Twig Extensions.
 */
class Loader extends \Twig_Extension {
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * The block manager.
   *
   * @var \Drupal\Core\Block\BlockManagerInterface
   */
  protected $blockManager;

  /**
   * The form builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * TwigExtension constructor.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, RouteMatchInterface $route_match, BlockManagerInterface $blockManager, $formBuilder) {
    $this->entityTypeManager   = $entity_type_manager;
    $this->routeMatch          = $route_match;
    $this->blockManager        = $blockManager;
    $this->formBuilder         = $formBuilder;
  }

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('load_block', [$this, 'loadBlock'], array('is_safe' => array('html'))),
      new \Twig_SimpleFunction('load_form', [$this, 'loadForm'], array('is_safe' => array('html'))),
      new \Twig_SimpleFunction('load_entity', [$this, 'loadEntity']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig.twig.loader';
  }

  /**
   * Load a given block with or whitout parameters.
   */
  public function loadBlock($block_id, $params = array()) {
    $instance = $this->blockManager->createInstance($block_id, $params);
    return $instance->build($params);
  }

  /**
   * Load a given block with or whitout parameters.
   */
  public function loadForm($module, $form, $params = array()) {
    return \Drupal::formBuilder()->getForm('Drupal\\' . $module . '\Form\\' . $form, $params);
  }

  /**
   * Returns the render array for an entity.
   *
   * @param string $entity_type
   *   The entity type.
   * @param mixed $id
   *   The ID of the entity to render.
   * @param string $view_mode
   *   (optional) The view mode that should be used to render the entity.
   * @param string $langcode
   *   (optional) For which language the entity should be rendered, defaults to
   *   the current content language.
   *
   * @return null|array
   *   A render array for the entity or NULL if the entity does not exist.
   */
  public function loadEntity($entity_type, $id = NULL, $view_mode = NULL, $langcode = NULL) {
    $entity = $id ?
      $this->entityTypeManager->getStorage($entity_type)->load($id) :
      $this->routeMatch->getParameter($entity_type);
    if ($entity) {
      $render_controller = $this->entityTypeManager->getViewBuilder($entity_type);
      return $render_controller->view($entity, $view_mode, $langcode);
    }
    return NULL;
  }

}
