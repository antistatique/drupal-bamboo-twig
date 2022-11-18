# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [6.0.0] - 2022-11-18
### Added
- add official support of drupal 9.5 & 10.0

### Changed
- drop support of drupal below 9.3.x
- bump major release number in order of using Drupal new semver system

### Fixed
- fix deprecated class name Twig_Extension for Drupal 10 compatibilities
- fix deprecation drupal_get_path for Drupal 10 compatibilities
- fix deprecation Symfony\Cmf\Component\Routing\RouteObjectInterface::ROUTE_OBJECT for Drupal 10 compatibilities
- fix call to deprecated method assert() for Drupal 10 compatibilities
- fix call to deprecated constant FILE_STATUS_PERMANENT for Drupal 10 compatibilities
- fix call to deprecated function file_create_url() for Drupal 10 compatibilities
- fix the function file_build_uri() has been deprecated for Drupal 10 compatibilities
- fix Deprecated function: strtr(): Passing null to parameter #1 for PHP 8.1 compatibilities

## [5.1.0] - 2022-10-21
### Changed
- drop support of drupal 8.8 & 8.9
- change bamboo_render_block to support block context-mapping

### Fixed
- fix docker running tests on Github Actions

### Security
- update linter phpdd 2.0.24 => 2.0.29

### Added
- add coverage for Drupal 9.3, 9.4 & 9.5
- add upgrade-status check

### Removed
- remove satackey/action-docker-layer-caching on Github Actions

## [5.0.0] - 2022-06-24
### Added
- replace drupal_ti by wengerk/drupal-for-contrib
- remove dependency on twig/extensions
- fix Issue #3168662 by Michael Humbert: docker-compose with phpunit not working
- enforce PHPCS integration via GithubActions
- close Issue #3247601 - add drupalci.yml file to install symfony/mime on testing container

## [5.0.0-alpha] - 2020-01-10
### Added
- close #3044811 - fix Drupal-CI Composer failure since Drupal 8.7.x+ - Update of drupal/coder squizlabs/php_codesniffer"
- close Issue #3090749 by wengerk: Drupal 9 Readiness

## [4.1.0] - 2018-08-25
### Added
- add `bamboo_has_permissions` - Issue #2955808
- add `bamboo_has_roles` - Issue #2955808
- fix Dependency namespacing in .info.yml file - Issue #2992564
- improve `bamboo_has_permissions` & `bamboo_has_roles` README doc - Issue #2955808

## [4.0.0] - 2018-07-20
### Added
- add i18n filter to get translation of entity: `bamboo_i18n_get_translation`.
- fix translatability of time_diff: `bamboo_extensions_time_diff`.
- refactoring complete loader & renderer to works on multilingual websites.
- improve i18n with better tests coverage.

## [3.3.0] - 2018-05-16
### Added
- Fix date diff calcul error - Issue #2966556.

## [3.2.0] - 2018-03-01
### Added
- add Travis CI.
- add Style CI.
- add badges to README.md.
- fix composer invalid warning.

## [3.1.0] - 2017-09-26
### Added
- add a filter to render views: `bamboo_render_views`.
- add some tests for the new `bamboo_render_views` filter.

## [3.0.0] - 2017-07-12
### Added
- fixed test fails for breaking change of latest Core (8.4) changes.

## [2.1.0] - 2017-07-12
### Added
- improve the usage of the `bamboo_extensions_time_diff`.
- choose the period between s|i|h|d|m|y. When enpty, automatically choose the most accurate one.
- apply or not the Humanize format

## [2.0.0] - 2017-06-06
### Added
- boosts performance by using lazy loading & improves the code quality with automated workflow.
- includes automated unit and kernel tests to ensure stability.

## [1.0.0] - 2017-03-19
### Added
- init module
- provides some Twig extensions with some useful functions and filters that can improve development experience.

[Unreleased]: https://github.com/antistatique/drupal-bamboo-twig/compare/6.0.0...HEAD
[6.0.0]: https://github.com/antistatique/drupal-bamboo-twig/compare/8.x-5.1...6.0.0
[5.1.0]: https://github.com/antistatique/drupal-bamboo-twig/compare/8.x-5.0...8.x-5.1
[5.0.0]: https://github.com/antistatique/drupal-bamboo-twig/compare/8.x-5.0-alpha...5.0.0
[5.0.0-alpha]: https://github.com/antistatique/drupal-bamboo-twig/compare/8.x-4.1...8.x-5.0-alpha
[4.1.0]: https://github.com/antistatique/drupal-bamboo-twig/compare/8.x-4.0...8.x-4.1
[4.0.0]: https://github.com/antistatique/drupal-bamboo-twig/compare/8.x-3.3...8.x-4.0
[3.3.0]: https://github.com/antistatique/drupal-bamboo-twig/compare/8.x-3.2...8.x-3.3
[3.2.0]: https://github.com/antistatique/drupal-bamboo-twig/compare/8.x-3.0...8.x-3.2
[3.1.0]: https://github.com/antistatique/drupal-bamboo-twig/compare/8.x-3.0...8.x-3.1
[3.0.0]: https://github.com/antistatique/drupal-bamboo-twig/compare/8.x-2.1...8.x-3.0
[2.1.0]: https://github.com/antistatique/drupal-bamboo-twig/compare/8.x-2.0...8.x-2.1
[2.0.0]: https://github.com/antistatique/drupal-bamboo-twig/compare/8.x-1.0...8.x-2.0
[1.0.0]: https://github.com/antistatique/drupal-bamboo-twig/releases/tags/8.x-1.0
