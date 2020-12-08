ARG BASE_IMAGE_TAG=8.9
FROM wengerk/drupal-for-contrib:${BASE_IMAGE_TAG}

ARG BASE_IMAGE_TAG
ENV BASE_IMAGE_TAG=${BASE_IMAGE_TAG}

# Install twig/extensions & symfony/mime as required by bamboo_twig_extensions
RUN COMPOSER_MEMORY_LIMIT=-1 composer require "twig/extensions:^1.5.4" "symfony/mime:^4.3" --no-interaction

# Override the default template for PHPUnit testing.
COPY phpunit.xml /opt/drupal/web/phpunit.xml
