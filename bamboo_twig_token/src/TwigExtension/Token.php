<?php

namespace Drupal\bamboo_twig_token\TwigExtension;

use Twig\TwigFunction;
use Drupal\bamboo_twig\TwigExtension\TwigExtensionBase;

/**
 * Provides a token rempalcement as Twig Extensions.
 */
class Token extends TwigExtensionBase {

  /**
   * List of all Twig functions.
   */
  public function getFunctions() {
    return [
      new TwigFunction('bamboo_token', [$this, 'substituteToken']),
    ];
  }

  /**
   * Unique identifier for this Twig extension.
   */
  public function getName() {
    return 'bamboo_twig_token.twig.token';
  }

  /**
   * Substitute a given tokens with appropriate value.
   *
   * @param string $token
   *   A replaceable token.
   * @param array $data
   *   (optional) An array of keyed objects. For simple replacement scenarios
   *   'node', 'user', and others are common keys, with an accompanying node or
   *   user object being the value. Some token types, like 'site', do not
   *   require any explicit information from $data and can be replaced even if
   *   it is empty.
   * @param array $options
   *   (optional) A keyed array of settings and flags to control the token
   *   replacement process.
   *
   * @return string
   *   The token value.
   *
   * @see \Drupal\Core\Utility\Token::replace()
   */
  public function substituteToken($token, array $data = [], array $options = []) {
    return $this->getToken()->replace("[$token]", $data, $options);
  }

}
