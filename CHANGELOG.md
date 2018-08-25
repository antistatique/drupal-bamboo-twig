CHANGELOG
---------

## NEXT RELEASE
 - add `bamboo_has_permissions` - Issue #2955808
 - add `bamboo_has_roles` - Issue #2955808
 - fix Dependency namespacing in .info.yml file - Issue #2992564
 - improve `bamboo_has_permissions` & `bamboo_has_roles` README doc - Issue #2955808

## 8.x-4.0 (2018-07-20)
 - add i18n filter to get translation of entity: `bamboo_i18n_get_translation`.
 - fix translatability of time_diff: `bamboo_extensions_time_diff`.
 - refactoring complete loader & renderer to works on multilingual websites.
 - improve i18n with better tests coverage.

## 8.x-3.3 (2018-05-16)
 - Fix date diff calcul error - Issue #2966556.

## 8.x-3.2 (2018-03-01)
 - add Travis CI.
 - add Style CI.
 - add badges to README.md.
 - fix composer invalid warning.

## 8.x-3.1 (2017-09-26)

Bamboo Twig 8.x-3.1 add some new features:
 - add a filter to render views: `bamboo_render_views`.
 - add some tests for the new `bamboo_render_views` filter.

## 8.x-3.0 (2017-07-12)

 - fixed test fails for breaking change of latest Core (8.4) changes.

## 8.x-2.1 (2017-07-12)

Bamboo Twig 8.x-2.1 improve the usage of the `bamboo_extensions_time_diff`.

It had the followings features:
 - Choose the period between s|i|h|d|m|y. When enpty, automatically choose the most accurate one.
 - Apply or not the Humanize format

## 8.x-2.0 (2017-06-06)

Bamboo Twig 8.x-2.0 has a lot of advantages and brings a lot of new features to the Twig landscape of Drupal 8.

It boosts performance by using lazy loading & improves the code quality with automated workflow.
It also includes automated unit and kernel tests to ensure stability.

## 8.x-1.0 (2017-03-19)

I'm proud to present the first stable release of Bamboo Twig for Drupal 8.

Bamboo Twig module provides some Twig extensions with some useful functions and filters that can improve development experience.
