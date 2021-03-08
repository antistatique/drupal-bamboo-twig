<?php

namespace Drupal\Tests\bamboo_twig\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\bamboo_twig_file\UrlHelper;
use Symfony\Component\Routing\RequestContext;

/**
 * Test of the UrlHelper class.
 */
class UrlHelperTest extends TestCase {

  /**
   * TestGenerateAbsoluteUrl.
   *
   * @dataProvider getGenerateAbsoluteUrlData()
   */
  public function testGenerateAbsoluteUrl($expected, $path, $pathinfo) {
    $stack = new RequestStack();
    $stack->push(Request::create($pathinfo));
    $helper = new UrlHelper($stack);

    $this->assertEquals($expected, $helper->getAbsoluteUrl($path));
  }

  /**
   * GetGenerateAbsoluteUrlData.
   */
  public function getGenerateAbsoluteUrlData() {
    // phpcs:disable Drupal.Arrays.Array.LongLineDeclaration
    return [
      ['http://localhost/foo.png', '/foo.png', '/foo/bar.html'],
      ['http://localhost/foo/foo.png', 'foo.png', '/foo/bar.html'],
      ['http://localhost/foo/foo.png', 'foo.png', '/foo/bar'],
      ['http://localhost/foo/bar/foo.png', 'foo.png', '/foo/bar/'],

      ['http://example.com/baz', 'http://example.com/baz', '/'],
      ['https://example.com/baz', 'https://example.com/baz', '/'],
      ['//example.com/baz', '//example.com/baz', '/'],

      ['http://localhost/foo/bar?baz', '?baz', '/foo/bar'],
      ['http://localhost/foo/bar?baz=1', '?baz=1', '/foo/bar?foo=1'],
      ['http://localhost/foo/baz?baz=1', 'baz?baz=1', '/foo/bar?foo=1'],

      ['http://localhost/foo/bar#baz', '#baz', '/foo/bar'],
      ['http://localhost/foo/bar?0#baz', '#baz', '/foo/bar?0'],
      ['http://localhost/foo/bar?baz=1#baz', '?baz=1#baz', '/foo/bar?foo=1'],
      ['http://localhost/foo/baz?baz=1#baz', 'baz?baz=1#baz', '/foo/bar?foo=1'],
    ];
  }

  /**
   * TestGenerateAbsoluteUrlWithRequestContext.
   *
   * @dataProvider getGenerateAbsoluteUrlRequestContextData
   */
  public function testGenerateAbsoluteUrlWithRequestContext($path, $baseUrl, $host, $scheme, $httpPort, $httpsPort, $expected) {
    if (!class_exists('Symfony\Component\Routing\RequestContext')) {
      $this->markTestSkipped('The Routing component is needed to run tests that depend on its request context.');
    }

    $requestContext = new RequestContext($baseUrl, 'GET', $host, $scheme, $httpPort, $httpsPort, $path);
    $helper = new UrlHelper(new RequestStack(), $requestContext);

    $this->assertEquals($expected, $helper->getAbsoluteUrl($path));
  }

  /**
   * TestGenerateAbsoluteUrlWithoutRequestAndRequestContext.
   *
   * @dataProvider getGenerateAbsoluteUrlRequestContextData
   */
  public function testGenerateAbsoluteUrlWithoutRequestAndRequestContext($path) {
    if (!class_exists('Symfony\Component\Routing\RequestContext')) {
      $this->markTestSkipped('The Routing component is needed to run tests that depend on its request context.');
    }

    $helper = new UrlHelper(new RequestStack());

    $this->assertEquals($path, $helper->getAbsoluteUrl($path));
  }

  /**
   * GetGenerateAbsoluteUrlRequestContextData.
   */
  public function getGenerateAbsoluteUrlRequestContextData() {
    return [
      ['/foo.png', '/foo', 'localhost', 'http', 80, 443, 'http://localhost/foo.png'],
      ['foo.png', '/foo', 'localhost', 'http', 80, 443, 'http://localhost/foo/foo.png'],
      ['foo.png', '/foo/bar/', 'localhost', 'http', 80, 443, 'http://localhost/foo/bar/foo.png'],
      ['/foo.png', '/foo', 'localhost', 'https', 80, 443, 'https://localhost/foo.png'],
      ['foo.png', '/foo', 'localhost', 'https', 80, 443, 'https://localhost/foo/foo.png'],
      ['foo.png', '/foo/bar/', 'localhost', 'https', 80, 443, 'https://localhost/foo/bar/foo.png'],
      ['/foo.png', '/foo', 'localhost', 'http', 443, 80, 'http://localhost:443/foo.png'],
      ['/foo.png', '/foo', 'localhost', 'https', 443, 80, 'https://localhost:80/foo.png'],
    ];
  }

  /**
   * TestGenerateAbsoluteUrlWithScriptFileName.
   */
  public function testGenerateAbsoluteUrlWithScriptFileName() {
    $request = Request::create('http://localhost/app/web/app_dev.php');
    $request->server->set('SCRIPT_FILENAME', '/var/www/app/web/app_dev.php');

    $stack = new RequestStack();
    $stack->push($request);
    $helper = new UrlHelper($stack);

    $this->assertEquals(
      'http://localhost/app/web/bundles/framework/css/structure.css',
      $helper->getAbsoluteUrl('/app/web/bundles/framework/css/structure.css')
    );
  }

  /**
   * TestGenerateRelativePath.
   *
   * @dataProvider getGenerateRelativePathData()
   */
  public function testGenerateRelativePath($expected, $path, $pathinfo) {
    if (!method_exists('Symfony\Component\HttpFoundation\Request', 'getRelativeUriForPath')) {
      $this->markTestSkipped('Your version of Symfony HttpFoundation is too old.');
    }

    $stack = new RequestStack();
    $stack->push(Request::create($pathinfo));
    $urlHelper = new UrlHelper($stack);

    $this->assertEquals($expected, $urlHelper->getRelativePath($path));
  }

  /**
   * GetGenerateRelativePathData.
   */
  public function getGenerateRelativePathData() {
    return [
      ['../foo.png', '/foo.png', '/foo/bar.html'],
      ['../baz/foo.png', '/baz/foo.png', '/foo/bar.html'],
      ['baz/foo.png', 'baz/foo.png', '/foo/bar.html'],

      ['http://example.com/baz', 'http://example.com/baz', '/'],
      ['https://example.com/baz', 'https://example.com/baz', '/'],
      ['//example.com/baz', '//example.com/baz', '/'],
    ];
  }

}
