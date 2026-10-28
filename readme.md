# Drago Project Preset Permission

Overlay of [drago-ex/project-preset](https://github.com/drago-ex/project-preset) that adds permissions to the composed project.

[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject-preset-permission.svg)](https://badge.fury.io/ph/drago-ex%2Fproject-preset-permission)
[![Coding Style](https://github.com/drago-ex/project-preset-permission/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project-preset-permission/actions/workflows/coding-style.yml)

## Requirements

- PHP >= 8.3, < 9
- [Drago Project](https://github.com/drago-ex/project)
- [drago-ex/project-preset](https://github.com/drago-ex/project-preset)

## Installation

```bash
composer require drago-ex/project-preset-permission
```

## Files copied into the project

The files are copied into the project by [drago-ex/project-tools](https://github.com/drago-ex/project-tools),
as set in `extra.drago-tools` of this package's `composer.json`.

## What it adds

- [Permissions (ACL)](https://github.com/drago-ex/permission) and [role management](https://github.com/drago-ex/project-permission) in the administration.
- The administration menu shows only the items the user may open.
- The add, edit and delete buttons of the blog and the static pages are shown only to roles with the write privilege.
- The roles of users come from the database; the web installer gives the administrator the `admin` role and imports
  the privileges of the packages.

## Permission providers

The access rules of the included packages are generated into the project by:

```bash
php vendor/bin/drago-commands
```
