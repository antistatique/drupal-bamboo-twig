ARG BASE_IMAGE_TAG=8.9
FROM wengerk/drupal-for-contrib:${BASE_IMAGE_TAG}

ARG BASE_IMAGE_TAG
ENV BASE_IMAGE_TAG=${BASE_IMAGE_TAG}

# Install twig/extensions & symfony/mime as required by bamboo_twig_extensions
RUN set -eux; \
  if [ "${BASE_IMAGE_TAG%%.*}" = "8" ]; then \
    COMPOSER_MEMORY_LIMIT=-1 composer require "symfony/mime:^4.3"; \
  fi; \
  if [ "${BASE_IMAGE_TAG%%.*}" = "9" ]; then \
    COMPOSER_MEMORY_LIMIT=-1 composer require "symfony/mime:^5.1.0"; \
  fi;
