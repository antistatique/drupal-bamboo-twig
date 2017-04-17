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

Use the `format_date_i18n` filter to return a date string in the right language.

- `date` string or DateTime

```twig
{# Print the formatted date using Drupal i18n. #}
<dt>Format date:</dt>
<dd>{{ node.changed.value|format_date_i18n('d M, h:i A') }}</dd>
```

**Files**

The `theme_url` function returns the absolute URL of a file in the
specified theme.

- `theme_name` string
- `relative/path/file.ext` string - relative path from theme root

```twig
{# Print the absolute URL to `hook.png` inside `stable` theme #}
{{ theme_url('stable', 'images/color/hook.png') }}
```

The `extension_guesser` filter returns the extension of a file based on its
mimeType.

- `mimeType` string

```twig
{# Print the extension of the `application/pdf` mimeType #}
{{ 'application/pdf'|extension_guesser() }}
```

**Loaders**

The `load_block` function returns a render array of the specified block.

- `block_name` string
- `params` array (optional)

```twig
{# Render the `system_powered_by_block` block #}
{{ load_block('system_powered_by_block') }}
```

The `load_entity` function returns a render array of the specified node. Can load a specific `view`

- `entity_type` string
- `id` int (optional)
- `view_mode` string (optional) - machine name of the view mode
- `langcode` string (optional) - defaults to current language

```twig
{# Render node with nid 1 #}
{{ load_entity('node', 1) }}

{# Render the teaser of node with nid 2 #}
{{ load_entity('node', 2, 'teaser') }}
```

The `load_form` function returns a render array of the specified Form.

- `module` string
- `formName` string
- `params` array (optional)

```twig
{# Render a the CronForm #}
{{ load_form('system', 'CronForm') }}
```

The `load_menu` function returns a render array of the specified menu.

- `menu_name` string
- `level` int (optional) - defaults to 1
- `depth` int (optional) - defaults to 0

```twig
{# Render a part of the admin menu #}
{{ load_menu('admin', 1, 2) }}
```

The `load_field` function returns a render array of an entity field.

- `field_name` string
- `entity_type` string
- `id` int (optional)
- `view_mode` string - defaults to "default"
- `langcode` string - defaults to current language

```twig
{# Load the title of node 1  #}
{{ load_field('title', 'node', 1) }}
```

The `load_region` function returns a render array of the specified region.

- `region` string
- `theme_name` string (optional) - defaults to default theme

```twig
{# Load the sidebar_first region for current theme. #}
{{ load_region('sidebar_first') }}
```

**Image Styles**

The `image_style_file` function returns an array of links to the rendered image
with specified styles.

- `fid` int
- `styles` array

```twig
{# Get thumbnail and large image styles from image with fid 12. #}
{% set images = image_style_file(12, {'thumb': 'thumbnail', 'lg': 'large'}) %}
```

**Configurations**

The `get_config` function returns the specified setting.

- `config_key` string
- `name` string

```twig
{# Get system mail setting #}
{% set settings = get_config('system.site', 'mail') %}
```

The `get_state` function returns the specified setting.

- `state_key` string

```twig
{# Get system.cron_last from state #}
{% set settings = get_state('system.cron_last') %}
```

**Security**

```twig
{# Retrieve the current user. #}
<dt>Current User:</dt>
<dd>{% set user = get_current_user() %}</dd>

{# Check Permission. #}
<dt>Permission of current User:</dt>
<dd>{{ has_permission('administer site configuration') ? 'TRUE' : 'FALSE' }}</dd>

{# Retrieve the current user. #}
<dt>Permission of User ID 1:</dt>
<dd>{{ has_permission('administer site configuration', 1) ? 'TRUE' : 'FALSE' }}</dd>

{# Check Roles. #}
<dt>Role of current User:</dt>
<dd>{{ has_role('authenticated') ? 'TRUE' : 'FALSE' }}</dd>

{# Retrieve the current user. #}
<dt>Role of User ID 1:</dt>
<dd>{{ has_role('authenticated', 1) ? 'TRUE' : 'FALSE' }}</dd>
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
