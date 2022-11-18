# BAMBOO TWIG

All the Twig features you missed until now.

A Drupal powered module.

|       Tests-CI        |        Style-CI         |        Downloads        |         Releases         |
|:----------------------:|:-----------------------:|:-----------------------:|:------------------------:|
| [![Build Status](https://github.com/antistatique/drupal-bamboo-twig/actions/workflows/ci.yml/badge.svg)](https://github.com/antistatique/drupal-bamboo-twig/actions/workflows/ci.yml) | [![Code styles](https://github.com/antistatique/drupal-bamboo-twig/actions/workflows/styles.yml/badge.svg)](https://github.com/antistatique/drupal-bamboo-twig/actions/workflows/styles.yml) | [![Downloads](https://img.shields.io/badge/downloads-8.x--5.0-green.svg?style=flat-square)](https://ftp.drupal.org/files/projects/bamboo_twig-8.x-5.0.tar.gz) | [![Latest Stable Version](https://img.shields.io/badge/release-v5.0-blue.svg?style=flat-square)](https://www.drupal.org/project/bamboo_twig/releases) |

The Bamboo Twig module provides some Twig extensions with some useful functions
and filters aimed to improve the development experience.

Bamboo Twig has a lot of advantages and brings a lot of new features to the Twig landscape of Drupal 8.
It boosts performance by using lazy loading, improves the code quality
with automated workflow. It also includes automated unit and kernel tests
to ensure stability.

## Use Bamboo Twig if

  - You need to "Format dates using Drupal I118n".
  - You need to "Render a Block".
  - You need to "Render a Region".
  - You need to "Render an Entity with view mode".
  - You need to "Load an Entity".
  - You need to "Render a Field".
  - You need to "Retrieve the current user".
  - You need to "Check permissions or roles".
  - You need to "Render a Form".
  - You need to "Deal with image styles".
  - You need to "Use Token".
  - You need to "Create an absolute URL from a theme".
  - You need to "Retrieve the Extension file from given mimeType".
  - You want to use a twig module which is design to works on multilingual websites.
  - You want to use a twig module that follows all the best practices.
  - You want to use a twig module that ensure stability with tests.
  - You want to use a twig module that will be compatible Drupal 9.

Bamboo Twig can do a lot more than that, but perhaps these are some of the
obvious uses of Bamboo Twig.

## Performances

For performances reasons, Bamboo Twig has been split into multiple sub-modules
for each topic he provides Twigs.

## Bamboo Twig versions

Bamboo Twig is available for Drupal 8, Drupal 9 & Drupal 10!

- If you are running Drupal `10.x`, use Bamboo Twig `6.0.x`.
- If you are running Drupal `9.x`, use Bamboo Twig `5.x`.
- If you are running Drupal `8.8.x`, use Bamboo Twig `5.0`.
- If you are running Drupal `8.7.x`, use Bamboo Twig `4.x`.

If you need some help to upgrade from the version `8.x-1.x` to `8.x-2.x` check
[the guide](https://www.drupal.org/docs/8/modules/bamboo-twig/migrate-from-8x-1x-to-8x-2x#comment-12051399) about it.

In the case you are looking for the documentation of version `8.x-1.x`, check
the [README.md](https://github.com/antistatique/drupal-bamboo-twig/blob/8.x-1.x/README.md)
or the previous [project page](https://www.drupal.org/node/2884024).

The version `8.x-2.1` is not compatible with Drupal `8.4.x`.
Drupal `8.4.x` brings some breaking change with tests and so you
must upgrade to `8.x-3.x` version of **Bamboo Twig**.

The version `8.x-5.x` is not compatible with Drupal `8.7.x`.
Drupal `8.8.x` brings some breaking change with tests and so you
must upgrade to `8.x-3.x` version of **Bamboo Twig**.

## Which version should I use?

| Drupal Core | Bamboo Twig |
|:-----------:|:-----------:|
|    8.0.x    |     1.x     |
|    8.4.x    |     2.x     |
|    8.7.x    |     4.x     |
|    8.8.x    |     5.0     |
|    8.9.x    |     5.0     |
|     9.x     |     5.x     |
|    10.x     |    6.0.x    |

## Dependencies

The Drupal 8, Drupal 9 & Drupal 10 version of Bamboo Twig requires nothing !
Feel free to use it.

## Similar modules

At first sight, Bamboo Twig offers similar functionality to the following modules,
although Bamboo Twig will normalize the way you use twig in your Drupal project,
thus reducing the need to install a bunch of extra modules.

Plus, it adds a lots of functionality, ensures stability with tests, includes automated
quality control and is totally open to contribution via [Github](https://github.com/antistatique/drupal-bamboo-twig) or [Drupal Issue Queue](https://www.drupal.org/project/issues/bamboo_twig).

Finally, Bamboo Twig follows all the best practices of Drupal 8 to ensure
compatibility with Drupal 9.

  - Only expose a set of Renderer functions & filters [Twig Tweak](https://www.drupal.org/project/twig_tweak).
  - Output clean Twig debug [Twig Clean Debug](https://www.drupal.org/project/twig_clean_debug).
  - Define and expose self-contained UI patterns [UI Patterns](https://www.drupal.org/project/ui_patterns).
  - Provides the [raulfraile/ladybug](https://github.com/raulfraile/ladybug) Dumper [Devel Ladybug](https://www.drupal.org/project/devel_ladybug).
  - Add attributes to link() item.url's [Twig Link Attributes](https://www.drupal.org/project/twig_link_attributes).
  - Reuse small self contained parts from theme templates [Partial](https://www.drupal.org/project/partial).
  - Embed Views [TVE](https://www.drupal.org/project/tve).
  - Port Twig Extensions to Drupal [Twig Extensions](https://www.drupal.org/project/twig_extensions).
  - Use a set of tools to debug Drupal [LGP](https://www.drupal.org/project/lgp).
  - Use a set of tools to deal with render arrays [Twig Renderable](https://www.drupal.org/project/twig_renderable).
  - Get partial data from field render arrays [Twig Field Value](https://www.drupal.org/project/twig_field_value).
  - Execute PHP code from Twig - **Do not use this module** - [Twig PHP](https://www.drupal.org/project/twig_php).

## Supporting organizations

This project is sponsored by [Antistatique](https://www.antistatique.net), a Swiss Web Agency.
Visit us at [www.antistatique.net](https://www.antistatique.net) or
[Contact us](mailto:info@antistatique.net).

## Getting Started

We highly recommend you to install the module using `composer`.

```bash
$ composer require drupal/bamboo_twig
```

You can also install it using the `drush` or `drupal console` cli.

```bash
$ drush dl bamboo_twig
```

```bash
$ drupal module:install bamboo_twig
 ```

Don't forget to enable the  modules you need from Bamboo Twig.

## Bamboo Twig Extensions

**Internationalization**

`date|bamboo_i18n_format_date('medium')` returns a date string in the correct locality.

- `date` string - date, timestamp, DrupalDateTimePlus, DateTimePlus or DateTime
- `type` string (optional) - The format to use, one of the built-in formats: 'short', 'medium', 'long'. Use 'custom' to use `format`.
- `format` string (optional) - PHP date format string suitable for input to date()
- `timezone` string (optional) - Time zone identifier, as described on [php.net/manual/timezones.php](http://php.net/manual/timezones.php), defaults to the timezone used to display the page.
- `langcode` string (optional) - defaults to current interface language.

```twig
{# Print the formatted date using Drupal i18n. #}
<p>Format date:</p>
{{ node.changed.value|bamboo_i18n_format_date('medium') }}<br>
{{ node.changed.value|bamboo_i18n_format_date('custom', 'Y-m-d') }}
```

`bamboo_i18n_current_lang()` returns the current lang iso code.

```twig
{# Print the current language ID. #}
<p>Current lang:</p>
{{ bamboo_i18n_current_lang() }}
```

`entity|bamboo_i18n_get_translation` returns the translated entity.

- `langcode` string (optional) - defaults to current interface language.

```twig
{# Get the French translation of an entity #}
<p>French title of entity:</p>
<p>{{ entity|bamboo_i18n_get_translation('fr').title.value }}</p>
<p>Title of entity using the current interface language:</p>
<p>{{ entity|bamboo_i18n_get_translation.title.value }}</p>
```

`bamboo_load_entity(entity_type, id)` returns the loaded entity. Use if you don't already have a `Drupal\Core\Entity\EntityInterface` object.

```twig
{# Load the entity node with nid 1 #}
{% set node = bamboo_load_entity('node', 1) %}
{# Then get the German translation of node #}
<p>German title of entity:</p>
<p>{{ node|bamboo_i18n_get_translation('de').title.value }}</p>
```

**Files**

`filetype|bamboo_file_extension_guesser` returns the extension of a file based on its
mimeType.

- `mimeType` string

```twig
{# Print the extension of the `application/pdf` mimeType #}
{{ 'application/pdf'|bamboo_file_extension_guesser }}
```

`bamboo_file_url_absolute(uri)` returns the absolute URL of a given URI
or path to a file.

- `uri` string - URI or string path to a file.

```twig
{# Print the absolute URL to access `image.jpg` #}
{{ bamboo_file_url_absolute('public://image.jpg') }}
```

**Paths**

`bamboo_path_system(type, item)` returns the relative URL of a system entity.

- `type` string - must be either of 'core', 'profile', 'module', 'theme' or 'theme_engine'.
- `item` string

```twig
{# Print the relative URL of the system entity `theme` named `stable` #}
{{ bamboo_path_system('theme', 'starterkit_theme') }}
```

**Loaders**

`bamboo_load_currentuser()` returns the User object of the current logged user.

```twig
{# Load the current user #}
{% set user = bamboo_load_currentuser() %}
```

`bamboo_load_entity(entity_type, id, langcode)` returns a EntityInterface object
of the requested entity.

- `entity_type` string
- `id` int (optional)
- `langcode` string (optional) - defaults to current context language

```twig
{# Load the entity node with nid 1 #}
{% set node = bamboo_load_entity('node', 1) %}
```

Keep in mind, when loading an entity it will fetch it in the current context language.
When you access it directly through a *EntityReferenceField* or a *Paragraph*
(e.g. `node.field_referenced_tags.entity`), the entity is always loaded in its original language.
(it won't be loaded in the current context language or in the entity language)
You should then use the `|bamboo_i18n_get_translation` filter to make sure you have the
entity displayed in another language.

```twig
{# Load the entity node with nid 1 #}
{% set node = bamboo_load_entity('node', 1) %}
{# Display the entity title in the current context lang (page language) #}
{{ node.title.value }}
{# Display the referenced entity name in its original lang #}
{{ node.field_referenced_tags.entity.name.value }}
{# Display the referenced entity name in the current context lang (page language) #}
{{ node.field_referenced_tags.entity|bamboo_i18n_get_translation.name.value }}
```

`bamboo_load_field(field, entity_type, id)` returns a FieldItemListInterface object of the requested field.

- `field` string
- `entity_type` string
- `id` int

```twig
{# Load the title of node 1 with nid 1 #}
{% set title = bamboo_load_field('title', 'node', 1) %}
```

Keep in mind, loading a field with `bamboo_load_field()` will fetch it in the
current context language.
When you access it directly through a *EntityReferenceField* or *Paragraph*,
the entity is always in its original language
(it won't be loaded in the current context language or in the entity language).
You should then use the `|bamboo_i18n_get_translation` filter to make sure you have the
entity displayed in another language.

```twig
{# Load the entity node with nid 1 #}
{% set title = bamboo_load_field('title', 'node', 1) %}
{# Display the entity title in the current context lang (page language) #}
{{ title.value }}
{% set tags = bamboo_load_field('field_tags', 'node', 1) %}
{# Display the entity name in his original lang #}
{{ tags.entity.name.value }}
{# Display the entity name in the current context lang (page language) #}
{{ tags.entity|bamboo_i18n_get_translation.name.value }}
```

`bamboo_load_image(path)` returns a ImageInterface object
of the requested image.

- `path` string - The path or URI to the original image.

```twig
{# Load image with URI `public://antistatique.jpg` #}
{% set image = bamboo_load_image('public://antistatique.jpg') %}
```

**Render**

`bamboo_render_block(block_name, params)` returns a render array of the
specified block (works only for Plugin Block).

- `block_name` string
- `params` array (optional)

```twig
{# Render the `system_powered_by_block` block #}
{{ bamboo_render_block('system_powered_by_block') }}
```

In case you want to render a Block Entity,
you have to use `bamboo_render_entity()`. See example below.

`bamboo_render_entity(entity_type, id, view_mode, langcode)` returns a render array of the specified
entity type. Can be rendered a specific `view_mode`.

- `entity_type` string
- `id` int (optional)
- `view_mode` string (optional) - machine name of the view mode
- `langcode` string (optional) - defaults to current language

```twig
{# Render node with nid 1 #}
{{ bamboo_render_entity('node', 1) }}

{# Render the teaser of node with nid 2 #}
{{ bamboo_render_entity('node', 2, 'teaser') }}

{# Render Block entity #}
{{ bamboo_render_entity('block', 'stark_messages') }}
```

`bamboo_render_form(module, formName)` returns a render array of the specified Form.

- `module` string
- `formName` string
- `params` array (optional)

```twig
{# Render a the CronForm #}
{{ bamboo_render_form('system', 'CronForm') }}
```

`bamboo_render_menu(menu_name, level, depth)` returns a render array of the specified menu.

- `menu_name` string
- `level` int (optional) - defaults to 1
- `depth` int (optional) - defaults to 0

```twig
{# Render a part of the admin menu #}
{{ bamboo_render_menu('admin', 1, 2) }}
```

`bamboo_render_field(field_name, entity_type, id, langcode, formatter)` returns a render array of an entity field.

- `field_name` string
- `entity_type` string
- `id` int (optional)
- `langcode` string (optional) - defaults to current language
- `formatter` string (optional) - The formatter that should be used to render the field

```twig
{# Render the title of node 1  #}
{{ bamboo_render_field('title', 'node', 1) }}
```

`bamboo_render_region(region, theme_name)` returns a render array of the specified region.

- `region` string
- `theme_name` string (optional) - defaults to default theme

```twig
{# Render the sidebar_first region for current theme. #}
{{ bamboo_render_region('sidebar_first') }}
```

**Image Styles**

`bamboo_render_image(fid, style)` returns a render array of the specified image file.

- `fid` int
- `style` string

```twig
{# Get thumbnail from image with fid 12. #}
{{ bamboo_render_image(1, 'thumbnail') }}
```

`bamboo_render_image_style(path, style, preprocess)` returns the URL string of the
specified image path or URI.

- `path` string
- `style` string
- `preprocess` boolean - preprocess the image style before first HTTP call.

```twig
{# Get thumbnail from image `public://antistatique.jpg`. #}
{{ bamboo_render_image_style('public://antistatique.jpg', 'thumbnail') }}
```

**Views**

`bamboo_render_views(view, item)` renders the requested view.

- `view` string
- `item` string

```twig
{# Render the `who_s_new` View `block_1` block #}
{{ bamboo_render_views('who_s_new', 'block_1') }}
```

**Configurations**

`bamboo_config_get(config_key, name)` returns the specified config value.

- `config_key` string
- `name` string

```twig
{# Get system mail setting value #}
{{ bamboo_config_get('system.site', 'mail') }}
```

`bamboo_state_get(state_key)` returns the specified state.

- `state_key` string

```twig
{# Get system.cron_last from state #}
{{ bamboo_state_get('system.cron_last') }}
```

`bamboo_settings_get(state_key)` returns the specified setting.

- `state_key` string

```twig
{# Get hash_salt from settings #}
{{ bamboo_settings_get('hash_salt') }}
```

**Security**

`bamboo_has_role(role, user)` returns a boolean if the current|given user
has the requested role.

- `role` string
- `user` (optional) int - User id instead of the current logged user.

```twig
{# Does the current|given user have the given role ? #}
{{ bamboo_has_role('authenticated') ? 'TRUE' : 'FALSE' }}
```

The `bamboo_has_roles` function returns a boolean if the current|given user
has the requested roles.

- `roles` string[]
- `conjunction` (optional) string - The conjunction to use on the set of roles.
  Only the two values 'AND' or 'OR' are allowed.
- `user` (optional) int - User id instead of the current logged user.

```twig
{# Does the current user have all the given roles ? #}
{{ bamboo_has_roles(['authenticated', 'administrator']) ? 'TRUE' : 'FALSE' }}
{# Does the current user have at least one of the given roles ? #}
{{ bamboo_has_roles(['authenticated', 'administrator'], 'OR') ? 'TRUE' : 'FALSE' }}
```

`bamboo_has_permission(permission, user)` returns TRUE if the current|given user
has the requested permission.

- `permission` string
- `user` (optional) int - User id instead of the current logged user.

```twig
{# Does the current|given user have the given permission ? #}
{{ bamboo_has_permission('administer site configuration') ? 'TRUE' : 'FALSE' }}
```

The `bamboo_has_permissions` function returns a boolean if the current|given user
has the requested permissions.

- `permissions` string[]
- `conjunction` (optional) string - The conjunction to use on the set of permissions.
  Only the two values 'AND' or 'OR' are allowed.
- `user` (optional) int - User id instead of the current logged user.

```twig
{# Does the current user have all the given permissions ? #}
{{ bamboo_has_permissions(['administer site configuration', 'bypass node access']) ? 'TRUE' : 'FALSE' }}
{# Does the current user have at least one of the given permissions ? #}
{{ bamboo_has_permissions(['administer site configuration', 'bypass node access'], 'OR') ? 'TRUE' : 'FALSE' }}
```

**Extensions**

`sentence|truncate(count, word, separator)` truncates a string.
From Twig-extensions [Text](http://twig-extensions.readthedocs.io/en/latest/text.html).

- `sentence` string
- `count` int
- `word` boolean - preserve whole words.
- `separator` string - add characters to signify the ellipsis

```twig
{# Truncate a sentence #}
{{ "This is a very long sentence."|truncate(2, false, '...') }}
```

*coming soon* `sentence|bamboo_truncate_html(count, word, ellipsis)` truncates HTML sentences and preserves tags.

- `sentence` string
- `count` int
- `word` boolean - preserve whole words.
- `ellipsis` string

```twig
{# Truncate a HTML sentence #}
{{ "<p>This <b>is a very</b> long sentence.</p>"|bamboo_truncate_html(2, false, '...') }}
```

`array|shuffle` randomizes an array
From Twig-extensions [Array](http://twig-extensions.readthedocs.io/en/latest/array.html).

- `array` array

```twig
{# Shuffle the given array #}
[1, 2, 3]|shuffle
```

`date|time_diff(date2)` returns a time difference between two dates.
From Twig-extensions [Date](http://twig-extensions.readthedocs.io/en/latest/date.html).

- `date` string - date, timestamp, DrupalDateTimePlus, DateTimePlus or DateTime
- `date2` string - date, timestamp, DrupalDateTimePlus, DateTimePlus or DateTime

```twig
{# Difference between two dates #}
{{ '24-07-2014 17:28:01'|time_diff('24-07-2014 17:28:06') }}
```

**Token**

`bamboo_token(token)` substitutes a given tokens with its appropriate value.

- `token` string

```twig
{# Substitute token #}
{{ bamboo_token('site:name') }}
```

## Core Twig

Drupal core already adds a [handful of custom functions](https://www.drupal.org/docs/8/theming/twig/functions-in-twig-templates) that are Drupal specific.

**i18n**

`string|trans` translates strings.

See the following [link](http://getlevelten.com/blog/mark-carver/drupal-8-twig-templates-and-translations) for more details.

**Urls**

`path(route, params, options)` returns an absolute URL from route name and parameters.

- `route` string
- `params` (optional) array
- `options` (optional) array

```twig
{# Generates URL from route 'entity.user.canonical'. #}
<a href="{{ path('entity.user.canonical', {'user': user.id}) }}">View user profile</a>
```

`link(text, url, params)` returns `<a>` tag for the URL.

- `text` string
- `url` string
- `params` (optional) array

```twig
{# Create a link with markup. #}
{{ link('Homepage', item.url, { 'class':['foo', 'bar', 'baz']} ) }}
```

`file_url(uri)` returns a relative URL of a given URI or path to a file.

- `uri` string - URI or string path to a file.

```twig
{# Generate a relative URI path to the file. #}
{{ file_url('public://antistatique.jpg') }}
```

**Attachments**

`attach_library(library)` attaches an asset library to the template,
and hence to the response.

- `library` string

```twig
{# Attaches an asset library to the template. #}
{{ attach_library('classy/node') }}
```
