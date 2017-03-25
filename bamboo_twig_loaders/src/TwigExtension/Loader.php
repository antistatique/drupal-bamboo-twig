<?php

namespace Drupal\bamboo_twig_loaders\TwigExtension;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Block\BlockManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

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
   * The menu link tree service.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuTree;

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * TwigExtension constructor.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, RouteMatchInterface $route_match, BlockManagerInterface $blockManager, FormBuilderInterface $formBuilder, MenuLinkTreeInterface $menu_tree, ConfigFactoryInterface $config_factory) {
    $this->entityTypeManager = $entity_type_manager;
    $this->routeMatch        = $route_match;
    $this->blockManager      = $blockManager;
    $this->formBuilder       = $formBuilder;
    $this->menuTree          = $menu_tree;
    $this->configFactory     = $config_factory;
  }

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('load_block', [$this, 'loadBlock'], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('load_form', [$this, 'loadForm'], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('load_entity', [$this, 'loadEntity']),
      new \Twig_SimpleFunction('load_region', [$this, 'loadRegion'], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('load_field', [$this, 'loadField']),
      new \Twig_SimpleFunction('load_menu', [$this, 'loadMenu'], ['is_safe' => ['html']]),
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
  public function loadBlock($block_id, $params = []) {
    $instance = $this->blockManager->createInstance($block_id, $params);
    return $instance->build($params);
  }

  /**
   * Load a given block with or whitout parameters.
   */
  public function loadForm($module, $form, $params = []) {
    return $this->formBuilder->getForm('Drupal\\' . $module . '\Form\\' . $form, $params);
  }

  /**
   * Returns the render array for an entity.
   *
   * @param string $entity_type
   *   The entity type.
   * @param mixed $id
   *   (optional) The ID of the entity to render.
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

  /**
   * Builds the render array of a given region.
   *
   * @param string $region
   *   The region to build.
   * @param string $theme
   *   (optional) The name of the theme to load the region. If it is not
   *   provided then default theme will be used.
   *
   * @return array
   *   A render array to display the region content.
   */
  public function loadRegion($region, $theme = NULL) {
    $blocks = $this->entityTypeManager->getStorage('block')->loadByProperties([
      'region' => $region,
      'theme'  => $theme ?: $this->configFactory->get('system.theme')->get('default'),
    ]);

    $view_builder = $this->entityTypeManager->getViewBuilder('block');

    $build = [];
    /* @var $blocks \Drupal\block\BlockInterface[] */
    foreach ($blocks as $id => $block) {
      $block_plugin = $block->getPlugin();
      if ($block_plugin instanceof TitleBlockPluginInterface) {
        $request = $this->requestStack->getCurrentRequest();
        if ($route = $request->attributes->get(RouteObjectInterface::ROUTE_OBJECT)) {
          $block_plugin->setTitle($this->titleResolver->getTitle($request, $route));
        }
      }
      $build[$id] = $view_builder->view($block);
    }

    return $build;
  }

  /**
   * Returns the render array for a single entity field.
   *
   * @param string $field_name
   *   The field name.
   * @param string $entity_type
   *   The entity type.
   * @param mixed $id
   *   (optional) The ID of the entity to render.
   * @param string $view_mode
   *   (optional) The view mode that should be used to render the field.
   * @param string $langcode
   *   (optional) Language code to load translation.
   *
   * @return null|array
   *   A render array for the field or NULL if the value does not exist.
   */
  public function loadField($field_name, $entity_type, $id = NULL, $view_mode = 'default', $langcode = NULL) {
    $entity = $id ?
        $this->entityTypeManager->getStorage($entity_type)->load($id) :
        $this->routeMatch->getParameter($entity_type);
    if ($langcode && $entity->hasTranslation($langcode)) {
      $entity = $entity->getTranslation($langcode);
    }
    if (isset($entity->{$field_name})) {
      return $entity->{$field_name}->view($view_mode);
    }
    return NULL;
  }

  /**
   * Returns the render array for Drupal menu.
   *
   * @param string $menu_name
   *   The name of the menu.
   * @param int $level
   *   (optional) Initial menu level.
   * @param int $depth
   *   (optional) Maximum number of menu levels to display.
   *
   * @return array
   *   A render array for the menu.
   */
  public function loadMenu($menu_name, $level = 1, $depth = 0) {
    $parameters = $this->menuTree->getCurrentRouteMenuTreeParameters($menu_name);

    // Adjust the menu tree parameters based on the block's configuration.
    $parameters->setMinDepth($level);

    // When the depth is configured to zero, there is no depth limit. When depth
    // is non-zero, it indicates the number of levels that must be displayed.
    // Hence this is a relative depth that we must convert to an actual
    // (absolute) depth, that may never exceed the maximum depth.
    if ($depth > 0) {
      $parameters->setMaxDepth(min($level + $depth - 1, $this->menuTree->maxDepth()));
    }

    $parameters->onlyEnabledLinks();
    $parameters->expandedParents = [];

    $tree = $this->menuTree->load($menu_name, $parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $this->menuTree->transform($tree, $manipulators);

    return $this->menuTree->build($tree);
  }

}
