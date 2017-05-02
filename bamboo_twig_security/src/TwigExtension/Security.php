<?php

namespace Drupal\bamboo_twig_security\TwigExtension;

use Drupal\bamboo_twig\TwigExtension\TwigExtensionBase;

/**
 * Provides a 'Security' Twig Extensions.
 */
class Security extends TwigExtensionBase {

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('bamboo_has_permission', [$this, 'hasPermission']),
      new \Twig_SimpleFunction('bamboo_has_role', [$this, 'hasRole']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig.twig.security';
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
    $currentUser = $this->getCurrentUser();
    if (is_null($user) && $currentUser->isAnonymous()) {
      return NULL;
    }
    $user_id = $currentUser->id();
    if (!is_null($user) && is_int($user)) {
      $user_id = $user;
    }

    $account = $this->getUserStorage()->load($user_id);
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
    $currentUser = $this->getCurrentUser();
    if (is_null($user) && $currentUser->isAnonymous()) {
      return NULL;
    }

    $user_id = $currentUser->id();
    if (!is_null($user) && is_int($user)) {
      $user_id = $user;
    }

    $account = $this->getUserStorage()->load($user_id);
    if (!$account) {
      return NULL;
    }
    return $account->hasRole($role);
  }

}
