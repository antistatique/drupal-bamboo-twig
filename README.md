# BAMBOO TWIG

Bamboo Twig. Drupal 8 powered module.

Bamboo Twig module provides some Twig extensions with some useful functions and filters that can improve development experience.

## You need Bamboo Twig if

  - You need to "Format date using Drupal I118n" from your twig.
  - You need to "Load a Block" from your twig.
  - You need to "Load an Entity with view mode" from your twig.
  - You need to "Load a Form" from your twig.
  - You need to "Load a ImageStyle" from your twig.
  - You need to "Retrieve the Extension file from given mimeType" from your twig.

Bamboo Twig can do a lot more than that, but those are some of the obvious uses of Bamboo Twig.

## Bamboo Twig versions

Bamboo Twig is only available for Drupal 8 !   
The module is ready to be used in Drupal 8, there are no known issues.

## Dependencies

The Drupal 8 version of Bamboo Twig requires [Disrupt Tools](https://www.drupal.org/sandbox/wengerk/2855304).

## Supporting organizations

This project is sponsored by Antistatique. We are a Swiss Web Agency, Visit us at [www.antistatique.net](https://www.antistatique.net) or Contact us.

## Examples

**Dates**

```twig
{# Format date using Drupal i118n. #}
<dt>Format date:</dt>
<dd>{{ node.changed.value|format_date_i18n('d M, h:i A') }}</dd>
```

**Files**

```twig
{# Retrieve file Url into theme. #}
<dt>Files:</dt>
<dd>{{ theme_url('bartik', 'images/required.svg') }}</dd>

{# Retrieve the Extension file from given mimeType. #}
<dt>Files:</dt>
<dd>{{ file.entity.mimeType|extension_guesser() }}</dd>
```

**Loaders**

```twig
{# Load a Block. #}
<dt>Block:</dt>
<dd>{{ load_block('bartik_powered') }}</dd>

{# Load an Entity. #}
<dt>Entity:</dt>
<dd>{{ load_entity('node', node.nid.value) }}</dd>

{# Load an Entity with view mode. #}
<dt>Entity:</dt>
<dd>{{ load_entity('node', node.nid.value, 'teaser') }}</dd>

{# Load a Form. #}
<dt>Form:</dt>
<dd>{{ load_form('contact', 'ContactForm') }}</dd>
```

**Image Styles**

```twig
{# Image Style from File ID. #}
<dt>Image Styles:</dt>
<dd>{% set images = image_style_field(node.field_image, {'thumb': 'thumbnail', 'lg': 'large'}) %}</dd>

{# Image Style from Field. #}
<dt>Image Styles:</dt>
<dd>{% set images = image_style_file(node.field_image.entity.fid.value, {'thumb': 'thumbnail', 'lg': 'large'}) %}</dd>
```

**Configurations**

```twig
{# Configuration from Config API. #}
<dt>Config API:</dt>
<dd>{% set settings = load_config('system.site') %}</dd>

{# Configuration from State API. #}
<dt>State API:</dt>
<dd>{% set settings = load_state('system.cron_last') %}</dd>
```
