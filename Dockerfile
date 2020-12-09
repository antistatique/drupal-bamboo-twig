ARG BASE_IMAGE_TAG=8.9
FROM wengerk/drupal-for-contrib:${BASE_IMAGE_TAG}

ARG BASE_IMAGE_TAG
ENV BASE_IMAGE_TAG=${BASE_IMAGE_TAG}

# Install symfony/mime as required by bamboo_twig_extensions
RUN COMPOSER_MEMORY_LIMIT=-1 composer require "symfony/mime:^4.3|^5.1.0"

# Register the Drupal and DrupalPractice Standard with PHPCS.
RUN ./vendor/bin/phpcs --config-set installed_paths \
    `pwd`/vendor/drupal/coder/coder_sniffer

# Copy the PHPCS definition files to ease PHPCS run.
COPY phpcs.xml.dist ./
