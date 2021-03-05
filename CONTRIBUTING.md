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
  * docker
  * docker-compose

### Project bootstrap

Once run, you will be able to access to your fresh installed Drupal on `localhost::8888`.

    docker-compose build --pull --build-arg BASE_IMAGE_TAG=8.9 drupal
    (get a coffee, this will take some time...)
    docker-compose up -d drupal
    docker-compose exec -u www-data drupal drush site-install standard --db-url="mysql://drupal:drupal@db/drupal" --site-name=Example -y

    # You may be interesed by reseting the admin passowrd of your Docker and install the module using those cmd.
    docker-compose exec drupal drush user:password admin admin
    docker-compose exec drupal drush en bamboo_twig

## 🏆 Tests

We use the [Docker for Drupal Contrib images](https://hub.docker.com/r/wengerk/drupal-for-contrib) to run testing on our project.

Run testing by stopping at first failure using the following command:

    docker-compose exec -u www-data drupal phpunit --group=bamboo_twig --no-coverage --stop-on-failure --configuration=/var/www/html/phpunit.xml

## 🚔 Check Drupal coding standards & Drupal best practices

During Docker build, the PHPCS Drupal & DrupalPractice are registered automatically and the `phpcs.xml.dist` file copied in the root directory.

### Command Line Usage

    docker-compose exec drupal bash

Check Drupal coding standards:

  ```
  $ docker-compose exec drupal ./vendor/bin/phpcs ./web/modules/contrib/bamboo_twig/
  ```

Automatically fix coding standards

  ```
  $ docker-compose exec drupal ./vendor/bin/phpcbf ./web/modules/contrib/bamboo_twig/
  ```

### Enforce code standards with git hooks

Maintaining code quality by adding the custom post-commit hook to yours.

  ```
  $ cat ./scripts/hooks/post-commit >> ./.git/hooks/post-commit
  ```
