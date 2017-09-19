# BAMBOO TWIG

Bamboo Twig. A Drupal 8 powered module.

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
  - You want to use a twig module that follows all the best practices.
  - You want to use a twig module that ensure stability with tests.
  - You want to use a twig module that will be compatible Drupal 9.

Bamboo Twig can do a lot more than that, but perhaps these are some of the
obvious uses of Bamboo Twig.

## Performances

For performances reasons, Bamboo Twig has been split into multiple sub-modules
for each topic he provides Twigs.

## Bamboo Twig versions

Bamboo Twig is only available for Drupal 8!
The module is ready to be used in Drupal 8, there are no known issues.

If you need some help to upgrade from the version 8.x-1.x to 8.x-2.x check
[the guide](https://www.drupal.org/docs/8/modules/bamboo-twig/migrate-from-8x-1x-to-8x-2x#comment-12051399) about it.

In the case you are looking for the documentation of version 8.x-1.x, check
the [README.md](https://github.com/antistatique/drupal-bamboo-twig/blob/8.x-1.x/README.md)
or the previous [project page](https://www.drupal.org/node/2884024).

The version `8.x-2.1` is not compatible with Drupal `8.4.x`.
Drupal `8.4.x` brings some breaking change with tests and so you
must upgrade to the the `8.x-3.x` version of **Bamboo Twig**.

## Dependencies

The Drupal 8 version of Bamboo Twig requires nothing !
Feel free to use it.

## Similar modules

From the first sight, Bamboo Twig offers similar functionality to the following modules.
It's excepted that Bamboo Twig will normalize the way you use twig
in your Drupal project reducing the need to install a bunch of extra modules.

Plus, it adds a lots of functionality, ensures stability with tests, includes automated quality control and open minded to contribute throught Github PR.
Finally, Bamboo Twig use all the best practices of Drupal 8 to ensure
compatibility with Drupal 9.

  - Only expose a set of Renderer functions & filters[Twig Tweak](https://www.drupal.org/project/twig_tweak).
  - Clean away twig debugging output [Twig Clean Up](https://www.drupal.org/project/twig_clean_debug).
  - Define and expose self-contained UI patterns [UI Patterns](https://www.drupal.org/project/ui_patterns).
  - Provides the raulfraile/ladybug Dumper [Devel Ladybug](https://www.drupal.org/project/devel_ladybug).
  - Add attributes to link() item.url's [Twig Link Attributes](https://www.drupal.org/project/twig_link_attributes).
  - Reuse small self contained parts of their theme templates [Partial](https://www.drupal.org/project/partial).
  - Allows to embed views [TVE](https://www.drupal.org/project/tve).
  - Porting Twig extensions to Drupal [Twig Extensions](https://www.drupal.org/project/twig_extensions).
  - Set of tools to debug Drupal [LGP](https://www.drupal.org/project/lgp).
  - Set of tools to deal with render arrays [Twig Renderable](https://www.drupal.org/project/twig_renderable).
  - Get partial data from field render arrays [Twig Field Value](https://www.drupal.org/project/twig_field_value).
  - This module allows you to execute PHP code from Twig - **Do not use this module** - [Twig PHP](https://www.drupal.org/project/twig_php).

## Supporting organizations

This project is sponsored by Antistatique. We are a Swiss Web Agency,
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

Use the `bamboo_i18n_format_date` filter to return a date string in the correct locality.

- `date` string - date, timestamp, DrupalDateTimePlus, DateTimePlus or DateTime
- `type` string (optional) - The format to use, one of the built-in formats: 'short', 'medium', 'long'. Use 'custom' to use $format.
- `format` string (optional) - PHP date format string suitable for input to date()
- `timezone` string (optional) - Time zone identifier, as described at http://php.net/manual/timezones.php Defaults to the time zone used to display the page.
- `langcode` string (optional) - Language code to translate to. NULL (default) means to use the user interface language for the page.

```twig
{# Print the formatted date using Drupal i18n. #}
<dt>Format date:</dt>
<dd>{{ node.changed.value|bamboo_i18n_format_date('medium') }}</dd>
<dd>{{ node.changed.value|bamboo_i18n_format_date('custom', 'Y-m-d') }}</dd>
```

Use the `bamboo_i18n_current_lang` function to return the current lang iso code.

```twig
{# Print the current language ID. #}
<dt>Current lang:</dt>
<dd>{{ bamboo_i18n_current_lang() }}</dd>
```

**Files**

The `bamboo_file_extension_guesser` filter returns the extension of a file based on its
mimeType.

- `mimeType` string

```twig
{# Print the extension of the `application/pdf` mimeType #}
{{ 'application/pdf'|bamboo_file_extension_guesser }}
```

The `bamboo_file_url_absolute` function returns absolute url of a given URI
or path to a file.

- `uri` string - URI or string path to a file.

```twig
{# Print the absolute url to access `image.jpg` #}
{{ bamboo_file_url_absolute('public://image.jpg') }}
```

**Paths**

The `bamboo_path_system` function returns the relative URL of a system entity.

- `type` string - one of 'core', 'profile', 'module', 'theme' or 'theme_engine'.
- `item` string

```twig
{# Print the relative URL to the system entity `theme` named `stable` #}
{{ bamboo_path_system('theme', 'stable') }}
```

**Loaders**

The `bamboo_load_currentuser` function returns a User object
of the current logged user.

```twig
{# Load the current user #}
{% set user = bamboo_load_currentuser() %}
```

The `bamboo_load_entity` function returns a EntityInterface object
of the requested entity.

- `entity_type` string
- `id` int (optional)

```twig
{# Load the entity node with nid 1 #}
{% set node = bamboo_load_entity('node', 1) %}
```

The `bamboo_load_field` function returns a FieldItemListInterface object
of the requested field.

- `field` string
- `entity_type` string
- `id` int

```twig
{# Load the title of node 1 with nid 1 #}
{% set title = bamboo_load_field('title', 'node', 1) %}
```

The `bamboo_load_image` function returns a ImageInterface object
of the requested image.

- `path` string - The path or URI to the original image.

```twig
{# Load image with uri `public://antistatique.jpg` #}
{% set image = bamboo_load_image('public://antistatique.jpg') %}
```

**Render**

The `bamboo_render_block` function returns a render array of the
specified block (works only for Block Plugin).

- `block_name` string
- `params` array (optional)

```twig
{# Render the `system_powered_by_block` block #}
{{ bamboo_render_block('system_powered_by_block') }}
```

In the case you want to render a Block Entity,
you have to use the `bamboo_render_entity`. See example below.

The `bamboo_render_entity` function returns a render array of the specified
entity type. Can render a specific `view`.

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

The `bamboo_render_form` function returns a render array of the specified Form.

- `module` string
- `formName` string
- `params` array (optional)

```twig
{# Render a the CronForm #}
{{ bamboo_render_form('system', 'CronForm') }}
```

The `bamboo_render_menu` function returns a render array of the specified menu.

- `menu_name` string
- `level` int (optional) - defaults to 1
- `depth` int (optional) - defaults to 0

```twig
{# Render a part of the admin menu #}
{{ bamboo_render_menu('admin', 1, 2) }}
```

The `bamboo_render_field` function returns a render array of an entity field.

- `field_name` string
- `entity_type` string
- `id` int (optional)
- `view_mode` string - defaults to "default"
- `langcode` string - defaults to current language

```twig
{# Render the title of node 1  #}
{{ bamboo_render_field('title', 'node', 1) }}
```

The `bamboo_render_region` function returns a render array of the
specified region.

- `region` string
- `theme_name` string (optional) - defaults to default theme

```twig
{# Render the sidebar_first region for current theme. #}
{{ bamboo_render_region('sidebar_first') }}
```

**Image Styles**

The `bamboo_render_image` function returns a render array of the
specified image file.

- `fid` int
- `styles` string

```twig
{# Get thumbnail from image with fid 12. #}
{{ bamboo_render_image(1, 'thumbnail') }}
```

The `bamboo_render_image_style` function returns URL string of the
specified image path or URI.

- `path` string
- `styles` string
- `preprocess` boolean - preprocess the image style before first HTTP call.

```twig
{# Get thumbnail from image `public://antistatique.jpg`. #}
{{ bamboo_render_image_style('public://antistatique.jpg', 'thumbnail') }}
```

**Views**

The `bamboo_render_views` function renders the requested view.

- `view` string
- `item` string

```twig
{# Render the View `who_s_new` block `block_1` #}
{{ bamboo_render_views('who_s_new', 'block_1') }}
```

**Configurations**

The `bamboo_config_get` function returns the specified config.

- `config_key` string
- `name` string

```twig
{# Get system mail setting #}
{{ bamboo_config_get('system.site', 'mail') }}
```

The `bamboo_state_get` function returns the specified state.

- `state_key` string

```twig
{# Get system.cron_last from state #}
{{ bamboo_state_get('system.cron_last') }}
```

The `bamboo_settings_get` function returns the specified setting.

- `state_key` string

```twig
{# Get hash_salt from settings #}
{{ bamboo_settings_get('hash_salt') }}
```

**Security**

The `bamboo_has_role` function returns a boolean of the current|given user
has the requested role.

- `role` string
- `user` int - User id instead of the current logged user.

```twig
{# Does the current|given user has the given role ? #}
{{ bamboo_has_role('authenticated') ? 'TRUE' : 'FALSE' }}
```

The `bamboo_has_permission` function returns a boolean of the current|given user
has the requested permission.

- `permission` string
- `user` int - User id instead of the current logged user.

```twig
{# Does the current|given user has the given permission ? #}
{{ bamboo_has_permission('administer site configuration') ? 'TRUE' : 'FALSE' }}
```

**Extensions**

The `truncate` filter from Twig-extensions [Text](http://twig-extensions.readthedocs.io/en/latest/text.html).

- `sentence` string
- `word` boolean - Truncat at the end of words.
- `ellipsis` string

```twig
{# Truncate a sentence #}
{{ "This is a very long sentence."|truncate(2, false, '...') }}
```

The *coming soon* `bamboo_truncate_html` filter to truncates sentences html and preserves tags.

- `sentence` string
- `word` boolean - Truncate at the end of words.
- `ellipsis` string

```twig
{# Truncate a HTML sentence #}
{{ "<p>This <b>is a very</b> long sentence.</p>"|bamboo_truncate_html(2, false, '...') }}
```

The `shuffle` filter from Twig-extensions [Array](http://twig-extensions.readthedocs.io/en/latest/array.html).

- `array` array

```twig
{# Shuffle the given array #}
[1, 2, 3]|shuffle
```

The `time_diff` filter from Twig-extensions [Date](http://twig-extensions.readthedocs.io/en/latest/date.html).

- `date` string - date, timestamp, DrupalDateTimePlus, DateTimePlus or DateTime

```twig
{# Difference between two dates #}
{{ '24-07-2014 17:28:01'|time_diff('24-07-2014 17:28:06') }}
```

**Token**

The `bamboo_token` function substitute a given tokens with appropriate value.

- `token` string

```twig
{# Substitute token #}
{{ bamboo_token('site:name') }}
```

## Core Twig

Drupal core already adds a [handful of custom functions](https://www.drupal.org/docs/8/theming/twig/functions-in-twig-templates) that are Drupal specific.

**i18n**

The `trans` tag to translate string.

See the following [link](http://getlevelten.com/blog/mark-carver/drupal-8-twig-templates-and-translations) for more details.

**Urls**

The `path` functions returns an absolute URL given a route name and parameters.

- `route` string
- `params` (optional) array
- `options` (optional) array

```twig
{# Generates URL from route 'entity.user.canonical'. #}
<a href="{{ path('entity.user.canonical', {'user': user.id}) }}">View user profile</a>
```

The `link` functions return `<a>` tag for the URL.

- `text` string
- `url` string
- `params` (optional) array

```twig
{# Create a link with markup. #}
{{ link('Homepage', item.url, { 'class':['foo', 'bar', 'baz']} ) }}
```

The `file_url` function returns a relative url of a given URI or path to a file.

- `uri` string - URI or string path to a file.

```twig
{# Generate a relative URI path to the file. #}
{{ file_url('public://antistatique.jpg') }}
```

**Attachments**

The `attach_library` function Attaches an asset library to the template,
and hence to the response.

- `library` string

```twig
{# Attaches an asset library to the template. #}
{{ attach_library('classy/node') }}
```
