<?php

namespace Drupal\bamboo_twig\TwigExtension;

use Drupal\file\Plugin\Field\FieldType\FileFieldItemList;

// Injection.
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Block\BlockManagerInterface;

/**
 * Provides a 'Loader' Twig Extensions.
 */
class Loader extends \Twig_Extension {

  /**
   * Language manager service.
   *
   * @var LanguageManagerInterface
   */
  private $language;

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
   * TwigExtension constructor.
   */
  public function __construct(LanguageManagerInterface $language, EntityTypeManagerInterface $entity_type_manager, RouteMatchInterface $route_match, BlockManagerInterface $blockManager) {
    $this->language          = $language;
    $this->entityTypeManager = $entity_type_manager;
    $this->routeMatch        = $route_match;
    $this->blockManager      = $blockManager;
  }

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('load_block', [$this, 'loadBlock'], array('is_safe' => array('html'))),
      new \Twig_SimpleFunction('load_form', [$this, 'loadForm'], array('is_safe' => array('html'))),
      new \Twig_SimpleFunction('load_entity', [$this, 'loadEntity']),
      new \Twig_SimpleFunction('load_term_parent', [$this, 'loadTermParent']),
      new \Twig_SimpleFunction('get_lang', [$this, 'getLang']),
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

  /**
   * Generate Image Style, with responsive format.
   *
   * @param FileFieldItemList $field
   *   Field File Entity to retreive cover and generate it.
   * @param array $styles
   *   Styles to be generated.
   *
   * @return array
   *   Generated link of styles
   */
  public function loadImageStyleField(FileFieldItemList $field, array $styles) {
    return $this->imageStyleGenerator->fromField($field, $styles);
  }

  /**
   * Generate Image Style, with responsive format.
   *
   * @param int $fid
   *   File id to generate.
   * @param array $styles
   *   Styles to be generated.
   *
   * @return array
   *   Generated link of styles
   */
  public function loadImageStyleFile($fid, array $styles) {
    return $this->imageStyleGenerator->fromFile($fid, $styles);
  }

  /**
   * Load a given node.
   *
   * With or whitout parameters.
   */
  public function loadNode($node_id, $params) {
    return $this->entityTypeManager->getStorage('node')->load($node_id);
  }

  /**
   * Load an image from file uri.
   *
   * @param string $file_uri
   *    File URI on the current server.
   *
   * @return \Drupal\Core\Image\ImageInterface
   *    An Image object.
   */
  public function loadImage($file_uri) {
    return $this->imageFactory->get($file_uri);
  }

  /**
   * Retrieve the current language.
   */
  public function getLang() {
    return $this->language->getCurrentLanguage()->getId();
  }

  /**
   * Load the direct taxonomy term.
   *
   * @param int $taxonomy_term_tid
   *    Taxonomy term id.
   *
   * @return TaxonomyTerm
   *    The parent of the given taxonomy term id.
   */
  public function loadTermParent($taxonomy_term_tid) {
    $parent = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadParents($taxonomy_term_tid);
    $parent = reset($parent);
    return $parent;
  }

}
