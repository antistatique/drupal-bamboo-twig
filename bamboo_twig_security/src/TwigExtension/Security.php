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
      new \Twig_SimpleFunction('bamboo_has_permissions', [$this, 'hasPermissions']),
      new \Twig_SimpleFunction('bamboo_has_role', [$this, 'hasRole']),
      new \Twig_SimpleFunction('bamboo_has_roles', [$this, 'hasRoles']),
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
   * Does the current|given user has the given permissions collection ?
   *
   * @param string[] $permissions
   *   Drupal permissions string.
   * @param string $conjunction
   *   (Optional) The conjunction to use againts user permissions.
   *   Allowing 'AND' or 'OR' values. When nothing is given 'AND' is used.
   * @param int $user
   *   (Optional) user id to check permission. Otherwise current user is used.
   *
   * @return bool
   *   True if the current|given user has all the given permissions. Otherwise FALSE.
   */
  public function hasPermissions(array $permissions, $conjunction = 'AND', $user = NULL) {
    // Sanitize the conjunction to AND / OR values.
    if (!in_array($conjunction, ['AND', 'OR'])) {
      $conjunction = 'AND';
    }

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

    foreach ($permissions as $permission) {
      if ($conjunction == 'OR' AND $account->hasPermission($permission) ) {
        return TRUE;
      }

      if ($conjunction == 'AND' AND !$account->hasPermission($permission) ) {
        return FALSE;
      }
    }

    return TRUE;
  }

  /**
   * Does the current|given user has the given role ?
   *
   * @param string $role
   *   Drupal role name.
   * @param int $user
   *   (Optional) user id to check role. Otherwise current user is used.
   *
   * @return bool
   *   True if the current|given user has the given role. Otherwise FALSE.
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

  /**
   * Does the current|given user has the given roles collection ?
   *
   * @param string[] $roles
   *   Drupal roles name.
   * @param string $conjunction
   *   (Optional) The conjunction to use againts user permissions.
   *   Allowing 'AND' or 'OR' values. When nothing is given 'AND' is used.
   * @param int $user
   *   (Optional) user id to check permission. Otherwise current user is used.
   *
   * @return bool
   *   True if the current|given user has the given permission. Otherwise FALSE.
   */
  public function hasRoles($roles, $conjunction = 'AND', $user = NULL) {
    // Sanitize the conjunction to AND / OR values.
    if (!in_array($conjunction, ['AND', 'OR'])) {
      $conjunction = 'AND';
    }

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

    foreach ($roles as $role) {
      if ($conjunction == 'OR' AND $account->hasRole($role) ) {
        return TRUE;
      }

      if ($conjunction == 'AND' AND !$account->hasRole($role) ) {
        return FALSE;
      }
    }

    return TRUE;
  }

}
