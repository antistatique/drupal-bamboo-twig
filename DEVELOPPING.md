# Developing on BAMBOO TWIG

* Issues should be filed at
https://www.drupal.org/project/issues/bamboo_twig
* Pull requests can be made against
https://github.com/antistatique/drupal-bamboo-twig/pulls

## 📦 Repositories

Github repo
  ```
  $ git remote add drupal git@git.drupal.org:project/bamboo_twig.git
  ```

Drupal repo
  ```
  $ git remote add github https://github.com/antistatique/drupal-bamboo-twig.git
  ```

## 🔧 Prerequisites

First of all, you will need to have the following tools installed
globally on your environment:

  * drush
  * Latest dev release of Drupal 8.x.

## 🏆 Tests

Bamboo Twig use BrowserTestBase to test
web-based behaviors and features.

For tests you need a working database connection and for browser tests
your Drupal installation needs to be reachable via a web server.
Copy the phpunit config file:

  ```bash
  $ cd core
  $ cp phpunit.xml.dist phpunit.xml
  ```

You must provide a `SIMPLETEST_BASE_URL`, Eg. `http://localhost`.
You must provide a `SIMPLETEST_DB`, Eg. `sqlite://localhost/build/bamboo_twig.sqlite`.

Run the functional tests:

  ```bash
  # You must be on the drupal-root folder - usually /web.
  $ cd web
  $ SIMPLETEST_DB="sqlite://localhost//tmp/bamboo_twig.sqlite" \
  SIMPLETEST_BASE_URL='http://sandbox.test' \
  ../vendor/bin/phpunit -c core \
  --group bamboo_twig
  ```

Debug using

  ```bash
  # You must be on the drupal-root folder - usually /web.
  $ cd web
  $ SIMPLETEST_DB="sqlite://localhost//tmp/bamboo_twig.sqlite" \
  SIMPLETEST_BASE_URL='http://sandbox.test' \
  ../vendor/bin/phpunit -c core \
  --group bamboo_twig \
  --printer="\Drupal\Tests\Listeners\HtmlOutputPrinter" --stop-on-error
  ```

You must provide a `BROWSERTEST_OUTPUT_DIRECTORY`,
Eg. `/path/to/webroot/sites/simpletest/browser_output`.

## 🚔 Check Drupal coding standards & Drupal best practices

You need to run composer before using PHPCS. Then register the Drupal
and DrupalPractice Standard with PHPCS:
`./vendor/bin/phpcs --config-set installed_paths
`pwd`/vendor/drupal/coder/coder_sniffer`

### Command Line Usage

Check Drupal coding standards:

  ```
  $ ./vendor/bin/phpcs --standard=Drupal --colors \
  --extensions=php,module,inc,install,test,profile,theme,css,info,md \
  --ignore=*/vendor/* ./
  ```

Check Drupal best practices:

  ```
  $ ./vendor/bin/phpcs --standard=DrupalPractice --colors \
  --extensions=php,module,inc,install,test,profile,theme,css,info,md \
  --ignore=*/vendor/* ./
  ```

Automatically fix coding standards

  ```
  $ ./vendor/bin/phpcbf --standard=Drupal --colors \
  --extensions=php,module,inc,install,test,profile,theme,css,info \
  --ignore=*/vendor/* ./
  ```

### Enforce code standards with git hooks

Maintaining code quality by adding the custom post-commit hook to yours.

  ```
  $ cat ./scripts/hooks/post-commit >> ./.git/hooks/post-commit
  ```
