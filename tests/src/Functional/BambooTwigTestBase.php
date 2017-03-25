<?php

namespace Drupal\Tests\bamboo_twig\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Has some additional helper methods to make test code more readable.
 */
abstract class BambooTwigTestBase extends BrowserTestBase {

  /**
   * We use the minimal profile because we want to test local actions.
   *
   * @var string
   */
  protected $profile = 'minimal';

  /**
   * Enables Twig debugging.
   */
  protected function debugOn() {
    // Enable debug, rebuild the service container, and clear all caches.
    $parameters = $this->container->getParameter('twig.config');
    if (!$parameters['debug']) {
      $parameters['debug'] = TRUE;
      $this->setContainerParameter('twig.config', $parameters);
      $this->rebuildContainer();
      $this->resetAll();
    }
  }

  /**
   * Disables Twig debugging.
   */
  protected function debugOff() {
    // Disable debug, rebuild the service container, and clear all caches.
    $parameters = $this->container->getParameter('twig.config');
    if ($parameters['debug']) {
      $parameters['debug'] = FALSE;
      $this->setContainerParameter('twig.config', $parameters);
      $this->rebuildContainer();
      $this->resetAll();
    }
  }

  /**
   * Asserts that the element with the given CSS selector is present.
   *
   * @param string $css_selector
   *   The CSS selector identifying the element to check.
   *
   * @throws Behat\Mink\Exception\ElementHtmlException
   *   When the condition is not fulfilled.
   *
   * @see \Behat\Mink\WebAssert::elementExists
   */
  public function assertElementPresent($css_selector) {
    $this->assertSession()->elementExists('css', $css_selector);
  }

  /**
   * Asserts that the element with the given CSS selector is present.
   *
   * @param string $css_selector
   *   The CSS selector identifying the element to check.
   * @param string $html
   *   Expected text.
   *
   * @throws Behat\Mink\Exception\ElementHtmlException
   *   When the condition is not fulfilled.
   *
   * @see \Behat\Mink\WebAssert::elementContains
   */
  public function assertElementContains($css_selector, $html) {
    $this->assertSession()->elementContains('css', $css_selector, $html);
  }

  /**
   * Passes if a link with the specified label is found.
   *
   * An optional link index may be passed.
   *
   * @param string $label
   *   Text between the anchor tags.
   * @param int $index
   *   (optional) Link position counting from zero.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   *   Thrown when element doesn't exist, or the link label is a different one.
   *
   * @see \Behat\Mink\WebAssert::linkExists
   */
  public function assertLinkLabelExist($label, $index = 0) {
    $this->assertSession()->linkExists($label, $index = 0);
  }

  /**
   * Passes if a link containing a given href (part) is found.
   *
   * @param string $href
   *   The full or partial value of the 'href' attribute of the anchor tag.
   * @param int $index
   *   (optional) Link position counting from zero.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   *   Thrown when element doesn't exist, or the link label is a different one.
   *
   * @see \Behat\Mink\WebAssert::linkByHrefExists
   */
  public function assertLinkUrlExist($href, $index = 0) {
    $this->assertSession()->linkByHrefExists($href, $index = 0);
  }

  /**
   * Asserts that the element with the given CSS selector is present.
   *
   * @param string $css_selector
   *   The CSS selector identifying the element to check.
   * @param int $count
   *   Expected count.
   * @param string $locator
   *   Container that must have $css_selector.
   *
   * @throws Behat\Mink\Exception\ExpectationException
   *   When the condition is not fulfilled.
   *
   * @see \Behat\Mink\WebAssert::elementsCount
   */
  public function assertElementCount($css_selector, $count, $locator = NULL) {
    $container = $locator ? $this->getSession()->getPage()->find('css', $locator) : NULL;
    $this->assertSession()->elementsCount('css', $css_selector, $count, $container);
  }

}
