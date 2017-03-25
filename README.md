# BAMBOO TWIG

Bamboo Twig. Drupal 8 powered module.

Bamboo Twig module provides some Twig extensions with some useful functions
and filters that can improve development experience.

## You need Bamboo Twig if

  - You need to "Format date using Drupal I118n" from your twig.
  - You need to "Load a Block" from your twig.
  - You need to "Load an Entity with view mode" from your twig.
  - You need to "Load a Form" from your twig.
  - You need to "Load a ImageStyle" from your twig.
  - You need to "Retrieve the Extension file from given mimeType"
  from your twig.

Bamboo Twig can do a lot more than that, but those are some of the
obvious uses of Bamboo Twig.

## Performances

For performances reasons, Bamboo Twig has been splitted in multiple sub-modules
for each topics he provides Twigs.

## Bamboo Twig versions

Bamboo Twig is only available for Drupal 8 !   
The module is ready to be used in Drupal 8, there are no known issues.

## Dependencies

The Drupal 8 version of Bamboo Twig requires
[Disrupt Tools](https://www.drupal.org/sandbox/wengerk/2855304).

## Supporting organizations

This project is sponsored by Antistatique. We are a Swiss Web Agency,
Visit us at [www.antistatique.net](https://www.antistatique.net) or
[Contact us](mailto:info@antistatique.net).

## Bamboo Twig Extensions

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
<dd>{{ load_block('system_powered_by_block') }}</dd>

{# Load an Entity. #}
<dt>Entity:</dt>
<dd>{{ load_entity('node', node.nid.value) }}</dd>

{# Load an Entity with view mode. #}
<dt>Entity:</dt>
<dd>{{ load_entity('node', node.nid.value, 'teaser') }}</dd>

{# Load a Form. #}
<dt>Form:</dt>
<dd>{{ load_form('contact', 'ContactForm') }}</dd>

{# Expand the whole tree of given menu. #}
<dt>Menu:</dt>
<dd>{{ drupal_menu('admin') }}</dd>

{# Specify menu level and depth. #}
<dt>Part of menu:</dt>
<dd>{{ load_menu('admin', 1, 2) }}</dd>

{# Load a Field. #}
<dt>Field:</dt>
<dd>{{ load_field('title', 'node', node.nid.value) }}</dd>

{# Load a region for current theme. #}
<dt>Region:</dt>
<dd>{{ load_region('sidebar_first') }}</dd>

{# Load a region from a specific theme. #}
<dt>Region:</dt>
<dd>{{ load_region('footer', 'bartik') }}</dd>
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

## Core Twig

Drupal core already adds a [handful of custom functions](https://www.drupal.org/docs/8/theming/twig/functions-in-twig-templates) that are Drupal specific.

**Views**

```twig
{# Load a View. #}
<dt>View:</dt>
<dd>{{ views_embed_view('who_s_new', 'block_1') }}</dd>
```

**i18n**

See the following [link](http://getlevelten.com/blog/mark-carver/drupal-8-twig-templates-and-translations) for more details.

```twig
{# Using Twig tag. #}
{% trans %}
  Submitted by {{ author.username }} on {{ node.created }}
{% endtrans %}

{# Using Twig filter. #}
<p class="submitted">{{ "Submitted by !author on @date"|t({ '!author': author, '@date': date }) }}</p>
```

**Urls**

```twig
{# Generates an absolute URL given a route name and parameters. #}
<a href="{{ path('entity.user.canonical', {'user': user.id}) }}">{{ 'View user profile'|t }}</a>

{# Create a link with markup. #}
{{ link('Homepage', item.url, { 'class':['foo', 'bar', 'baz']} ) }}

{# Generate a relative URI path to the file from a given relative path from the root. #}
{{ file_url(node.field_example_image.entity.uri.value) }}
```

**Attachments**

```twig
{# Attaches an asset library to the template. #}
{{ attach_library('classy/node') }}
```
