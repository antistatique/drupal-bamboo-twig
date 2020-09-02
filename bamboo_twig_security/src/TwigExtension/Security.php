<?php

namespace Drupal\bamboo_twig_security\TwigExtension;

use Twig\TwigFunction;
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
      new TwigFunction('bamboo_has_permission', [$this, 'hasPermission']),
      new TwigFunction('bamboo_has_permissions', [$this, 'hasPermissions']),
      new TwigFunction('bamboo_has_role', [$this, 'hasRole']),
      new TwigFunction('bamboo_has_roles', [$this, 'hasRoles']),
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
    // Get the current user when $user is not provided.
    if (!$user) {
      $user = $this->getCurrentUser()->id();
    }
    $account = $this->getUserStorage()->load($user);

    // If given user do not exists or is anonymous - don't go further.
    if (!$account || $account->isAnonymous()) {
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
   *   Allowing 'AND' or 'OR' values. Default to 'AND'.
   * @param int $user
   *   (Optional) user id to check permission. Otherwise current user is used.
   *
   * @return bool
   *   True if the current|given user has all the given permissions.
   *   Otherwise FALSE.
   */
  public function hasPermissions(array $permissions, $conjunction = 'AND', $user = NULL) {
    // Get the current user when $user is not provided.
    if (!$user) {
      $user = $this->getCurrentUser()->id();
    }
    $account = $this->getUserStorage()->load($user);

    // If given user do not exists or is anonymous - don't go further.
    if (!$account || $account->isAnonymous()) {
      return NULL;
    }

    // Sanitize the conjunction to AND / OR values.
    if (!in_array($conjunction, ['AND', 'OR'])) {
      throw new \InvalidArgumentException(sprintf('Invalid conjunction type "%s".', $conjunction));
    }

    foreach ($permissions as $permission) {
      // When OR is requested, return TRUE on any match.
      if ($conjunction == 'OR' and $account->hasPermission($permission)) {
        return TRUE;
      }

      // When AND is requested, return FALSE on any unmatch.
      if ($conjunction == 'AND' and !$account->hasPermission($permission)) {
        return FALSE;
      }
    }

    // The previous loop may not return when:
    // - The conjunction is AND & the user has all roles.
    // - The conjunction is OR & the user has not any roles.
    return $conjunction == 'AND' ? TRUE : FALSE;
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
    // Get the current user when $user is not provided.
    if (!$user) {
      $user = $this->getCurrentUser()->id();
    }
    $account = $this->getUserStorage()->load($user);

    // If given user do not exists or is anonymous - don't go further.
    if (!$account || $account->isAnonymous()) {
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
   *   Allowing 'AND' or 'OR' values. Default to 'AND'.
   * @param int $user
   *   (Optional) user id to check permission. Otherwise current user is used.
   *
   * @return bool
   *   True if the current|given user has the given permission. Otherwise FALSE.
   */
  public function hasRoles(array $roles, $conjunction = 'AND', $user = NULL) {
    // Get the current user when $user is not provided.
    if (!$user) {
      $user = $this->getCurrentUser()->id();
    }
    $account = $this->getUserStorage()->load($user);

    // If given user do not exists or is anonymous - don't go further.
    if (!$account || $account->isAnonymous()) {
      return NULL;
    }

    // Sanitize the conjunction to AND / OR values.
    if (!in_array($conjunction, ['AND', 'OR'])) {
      throw new \InvalidArgumentException(sprintf('Invalid conjunction type "%s".', $conjunction));
    }

    foreach ($roles as $role) {
      // When OR is requested, return TRUE on any match.
      if ($conjunction == 'OR' and $account->hasRole($role)) {
        return TRUE;
      }

      // When AND is requested, return FALSE on any unmatch.
      if ($conjunction == 'AND' and !$account->hasRole($role)) {
        return FALSE;
      }
    }

    // The previous loop may not return when:
    // - The conjunction is AND & the user has all roles.
    // - The conjunction is OR & the user has not any roles.
    return $conjunction == 'AND' ? TRUE : FALSE;
  }

}
