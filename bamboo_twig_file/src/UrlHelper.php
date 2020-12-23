<?php

namespace Drupal\bamboo_twig_file;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RequestContext;

/**
 * A helper service for manipulating URLs within and outside the request scope.
 *
 * This file is backported from Symfony 4.3+
 * https://github.com/symfony/symfony/blob/5.x/src/Symfony/Component/HttpFoundation/UrlHelper.php.
 *
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
final class UrlHelper {

  /**
   * The Symfony Request Stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  private $requestStack;

  /**
   * The Request Context.
   *
   * @var \Symfony\Component\Routing\RequestContext|null
   */
  private $requestContext;

  /**
   * Constructor.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The Symfony Request Stack.
   * @param \Symfony\Component\Routing\RequestContext|null $requestContext
   *   The Request Context.
   */
  public function __construct(RequestStack $requestStack, RequestContext $requestContext = NULL) {
    $this->requestStack = $requestStack;
    $this->requestContext = $requestContext;
  }

  /**
   * Transform a relative path to an absolute URL.
   */
  public function getAbsoluteUrl(string $path): string {
    if (FALSE !== strpos($path, '://') || '//' === substr($path, 0, 2)) {
      return $path;
    }

    if (NULL === $request = $this->requestStack->getMasterRequest()) {
      return $this->getAbsoluteUrlFromContext($path);
    }

    if ('#' === $path[0]) {
      $path = $request->getRequestUri() . $path;
    }
    elseif ('?' === $path[0]) {
      $path = $request->getPathInfo() . $path;
    }

    if (!$path || '/' !== $path[0]) {
      $prefix = $request->getPathInfo();
      $last = \strlen($prefix) - 1;
      if ($last !== $pos = strrpos($prefix, '/')) {
        $prefix = substr($prefix, 0, $pos) . '/';
      }

      return $request->getUriForPath($prefix . $path);
    }

    return $request->getSchemeAndHttpHost() . $path;
  }

  /**
   * Transform an absolute url to a relative path.
   */
  public function getRelativePath(string $path): string {
    if (FALSE !== strpos($path, '://') || '//' === substr($path, 0, 2)) {
      return $path;
    }

    if (NULL === $request = $this->requestStack->getMasterRequest()) {
      return $path;
    }

    return $request->getRelativeUriForPath($path);
  }

  /**
   * Internal method to get the absolute url from the context.
   */
  private function getAbsoluteUrlFromContext(string $path): string {
    if (NULL === $this->requestContext || '' === $host = $this->requestContext->getHost()) {
      return $path;
    }

    $scheme = $this->requestContext->getScheme();
    $port = '';

    if ('http' === $scheme && 80 !== $this->requestContext->getHttpPort()) {
      $port = ':' . $this->requestContext->getHttpPort();
    }
    elseif ('https' === $scheme && 443 !== $this->requestContext->getHttpsPort()) {
      $port = ':' . $this->requestContext->getHttpsPort();
    }

    if ('#' === $path[0]) {
      $queryString = $this->requestContext->getQueryString();
      $path = $this->requestContext->getPathInfo() . ($queryString ? '?' . $queryString : '') . $path;
    }
    elseif ('?' === $path[0]) {
      $path = $this->requestContext->getPathInfo() . $path;
    }

    if ('/' !== $path[0]) {
      $path = rtrim($this->requestContext->getBaseUrl(), '/') . '/' . $path;
    }

    return $scheme . '://' . $host . $port . $path;
  }

}
