<?php

namespace Drupal\bamboo_twig_security\TwigExtension;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides a 'Security' Twig Extensions.
 */
class Security extends \Twig_Extension {

  /**
   * The account object.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The storage handler class for users.
   *
   * @var \Drupal\user\UserStorage
   */
  protected $userStorage;

  /**
   * TwigExtension constructor class.
   *
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   The current user.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity
   *   The Entity type manager service.
   */
  public function __construct(AccountInterface $currentUser, EntityTypeManagerInterface $entity) {
    $this->currentUser = $currentUser;
    $this->userStorage = $entity->getStorage('user');
  }

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('get_current_user', [$this, 'getCurrentUser']),
      new \Twig_SimpleFunction('has_permission', [$this, 'hasPermission']),
      new \Twig_SimpleFunction('has_role', [$this, 'hasRole']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig.twig.security';
  }

  /**
   * Return the current user.
   *
   * @return \Drupal\Core\Session\AccountInterface|null
   *   The current user AccountInterface or NULL when anonymous.
   */
  public function getCurrentUser($user = NULL) {
    if ($this->currentUser->isAnonymous()) {
      return NULL;
    }
    return $this->userStorage->load($this->currentUser->id());
  }

  /**
   * Does the current|given user has the given permission ?
   *
   * @param string $permission
   *   Drupal permission string.
   * @param int $user
   *   (Optional) user id to check permission. Otherwise current user is used.
   *
   * @return bool
   *   True if the current|given user has the given permission. Otherwise FALSE.
   */
  public function hasPermission($permission, $user = NULL) {
    if (is_null($user) && $this->currentUser->isAnonymous()) {
      return NULL;
    }

    $user_id = $this->currentUser->id();
    if (!is_null($user) && is_int($user)) {
      $user_id = $user;
    }

    $account = $this->userStorage->load($user_id);
    if (!$account) {
      return NULL;
    }
    return $account->hasPermission($permission);
  }

  /**
   * Does the current|given user has the given permission ?
   *
   * @param string $role
   *   Drupal role name.
   * @param int $user
   *   (Optional) user id to check permission. Otherwise current user is used.
   *
   * @return bool
   *   True if the current|given user has the given permission. Otherwise FALSE.
   */
  public function hasRole($role, $user = NULL) {
    if (is_null($user) && $this->currentUser->isAnonymous()) {
      return NULL;
    }

    $user_id = $this->currentUser->id();
    if (!is_null($user) && is_int($user)) {
      $user_id = $user;
    }

    $account = $this->userStorage->load($user_id);
    if (!$account) {
      return NULL;
    }
    return $account->hasRole($role);
  }

}