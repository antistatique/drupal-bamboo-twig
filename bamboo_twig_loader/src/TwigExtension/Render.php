<?php

namespace Drupal\bamboo_twig_loader\TwigExtension;

use Drupal\bamboo_twig\TwigExtension\TwigExtensionBase;
use Drupal\Core\Block\TitleBlockPluginInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;

/**
 * Provides some renderer as Twig Extensions.
 */
class Render extends TwigExtensionBase {

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('bamboo_render_block', [$this, 'renderBlock'], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('bamboo_render_form', [$this, 'renderForm'], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('bamboo_render_entity', [$this, 'renderEntity'], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('bamboo_render_region', [$this, 'renderRegion'], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('bamboo_render_field', [$this, 'renderField'], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('bamboo_render_image', [$this, 'renderImage'], ['is_safe' => ['html']]),
      new \Twig_SimpleFunction('bamboo_render_menu', [$this, 'renderMenu'], ['is_safe' => ['html']]),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig_loader.twig.render';
  }

  /**
   * Load a given block with or whitout parameters.
   *
   * @param string $block_id
   *   The ID of the block to render.
   * @param array $params
   *   (optional) An array of parameters passed to the block.
   *
   * @return null|array
   *   A render array for the block or NULL if the block does not exist.
   */
  public function renderBlock($block_id, $params = []) {
    $instance = $this->getPluginManagerBlock()->createInstance($block_id, $params);
    return $instance->build($params);
  }

  /**
   * Load a given form with or whitout parameters.
   *
   * @param string $module
   *   The module name where the form below.
   * @param string $form
   *   The form class name.
   * @param array $params
   *   (optional) An array of parameters passed to the form.
   *
   * @return null|array
   *   A render array for the form or NULL if the form does not exist.
   */
  public function renderForm($module, $form, $params = []) {
    return $this->getFormBuilder()->getForm('Drupal\\' . $module . '\Form\\' . $form, $params);
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
  public function renderEntity($entity_type, $id = NULL, $view_mode = NULL, $langcode = NULL) {
    // Lazy load the entity type manager only when needed.
    $entityTypeManager = $this->getEntityTypeManager();

    $entity = $id ?
      $entityTypeManager->getStorage($entity_type)->load($id) :
      $this->getCurrentRouteMatch()->getParameter($entity_type);

    if ($entity) {
      $render_controller = $entityTypeManager->getViewBuilder($entity_type);
      return $render_controller->view($entity, $view_mode, $langcode);
    }
    return NULL;
  }

  /**
   * Returns the render array for an image style.
   *
   * @param int $id
   *   The image File ID of the entity to render.
   * @param string $style
   *   The image style.
   *
   * @return string
   *   A render array for the image style or NULL if the image does not exist.
   */
  public function renderImage($id, $style) {
    $file = $this->getFileStorage()->load($id);

    // Check the entity exist.
    if ($file) {
      return [
        '#theme'      => 'image_style',
        '#style_name' => $style,
        '#uri'        => $file->getFileUri(),
      ];
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
  public function renderRegion($region, $theme = NULL) {
    $blocks = $this->getBlockStorage()->loadByProperties([
      'region' => $region,
      'theme'  => $theme ?: $this->getConfigFactory()->get('system.theme')->get('default'),
    ]);

    $view_builder = $this->getEntityTypeManager()->getViewBuilder('block');

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
   * @param string $formatter
   *   (optional) The formatter that should be used to render the field.
   * @param string $langcode
   *   (optional) Language code to load translation.
   *
   * @return null|array
   *   A render array for the field or NULL if the value does not exist.
   */
  public function renderField($field_name, $entity_type, $id = NULL, $formatter = NULL, $langcode = NULL) {
    $entity = $id ?
        $this->getEntityTypeManager()->getStorage($entity_type)->load($id) :
        $this->getCurrentRouteMatch()->getParameter($entity_type);

    // Ensure the entity has the requested field.
    if (!$entity->hasField($field_name)) {
      return NULL;
    }

    // Do not continue if the field is empty.
    if ($entity->get($field_name)->isEmpty()) {
      return NULL;
    }

    if ($langcode && $entity->hasTranslation($langcode)) {
      $entity = $entity->getTranslation($langcode);
    }

    $display_options = ['label' => 'hidden'];
    if (!is_null($formatter)) {
      $display_options['type'] = $formatter;
    }
    else {
      // We don't have the formatter view display and should fallback on
      // the default formatter.
      $field_type_definition = $this->getFieldTypeManager()->getDefinition($entity->getFieldDefinition($field_name)->getType());
      $display_options['type'] = $field_type_definition['default_formatter'];
    }

    if (isset($entity->{$field_name})) {
      return $entity->{$field_name}->view($display_options);
    }
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
  public function renderMenu($menu_name, $level = 1, $depth = 0) {
    // Lazy load the entity type manager only when needed.
    $menuLinkTree = $this->getMenuLinkTree();

    $parameters = $menuLinkTree->getCurrentRouteMenuTreeParameters($menu_name);

    // Adjust the menu tree parameters based on the block's configuration.
    $parameters->setMinDepth($level);

    // When the depth is configured to zero, there is no depth limit. When depth
    // is non-zero, it indicates the number of levels that must be displayed.
    // Hence this is a relative depth that we must convert to an actual
    // (absolute) depth, that may never exceed the maximum depth.
    if ($depth > 0) {
      $parameters->setMaxDepth(min($level + $depth - 1, $menuLinkTree->maxDepth()));
    }

    $parameters->onlyEnabledLinks();
    $parameters->expandedParents = [];

    $tree = $menuLinkTree->load($menu_name, $parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $menuLinkTree->transform($tree, $manipulators);

    return $menuLinkTree->build($tree);
  }

}
