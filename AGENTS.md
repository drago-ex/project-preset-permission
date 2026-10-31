# Agent Guide

Use this file as package-specific guidance when working in this repository. Keep changes aligned with the existing public API, project conventions, and supported PHP range.

## Package Overview
- **Package**: `drago-ex/project-preset-permission`
- **Type**: drago-tools-resource
- **Purpose**: Overlay of project-preset that adds permissions: menu by privileges, write buttons and database roles.
- **PHP**: >=8.3 <9
- **Runtime dependencies**: drago-ex/translator (^4.0.1), drago-ex/project-preset (^2.0), drago-ex/project-install (^1.0), drago-ex/permission (^1.0), drago-ex/project-permission (^2.0), drago-ex/datagrid (^1.0), drago-ex/database (^5.0), drago-ex/project-blog (^2.0), drago-ex/project-static-page (^2.0)
- **License**: MIT

## Repository Layout
- `resources/`: application resources contributed to the consuming Drago project.
- `.github/`: CI workflows; keep their PHP versions and commands consistent with Composer and the package scripts.
- `composer.json`: package metadata, dependency constraints, autoloading, and available Composer scripts.

## Key Parts
- Why this package (user, 2026-10-27): no package and not project-preset contain anything about permissions; this overlay adds all of it to a project composed by project-preset, so a project can have the full stack with or without permissions. `create-netis` installs both. `resources/app` and `resources/assets` are installed by `replace` (`replace-once`, `replace-priority` 310, after project-preset's 300).
- Every file here is a copy of a file of another package plus only the permission changes, so when such a file changes in its package, copy it again and redo the changes (otherwise the project gets a stale fork, as project-preset's old `Backend/@layout.latte` without the `noindex` robots meta was):
  - From project-preset: `Presentation/BasePresenter.php` (+ `Drago\Permission\Authorization`, `isReadOnlySignal()` returning `DataGrid::isReadOnlySignal()`: sorting, filtering and paging a grid is checked as `{component}-read`, and so are the signals of a presenter's `readOnlySignals()`/`readOnlyReceivers()`), `Presentation/Front/@layout.latte` (the administration item of the user dropdown only with `Backend:Admin` `default-read`), `assets/admin.js` + `admin.scss` (+ the `PermissionToggle` Naja extension and styles of project-permission).
  - From project-backend-ui: `Backend/Accessory/Menu/` (`SidebarMenu::build($currentUser)` with `filter()`, `SidebarBuilder::setAllowAny()`, `allowAny`/`allow` in `SidebarItem`/`SidebarSubItem`, checked through `CurrentUser::isPermitted()`; without an authorizator all items are shown, an unknown resource is denied, an item whose sub-items are all hidden is hidden), `Backend/BackendPresenter.php` (passes `$currentUser`), `Backend/@layout.latte` (the dashboard brand and breadcrumb only with `Backend:Admin` `default-read`).
  - From project-auth: `Model/User/UserRepository.php` (`getRolesByUser()` from the tables of project-permission instead of `[]`).
  - From project-blog and project-static-page: `Backend/Blog/Component` and `Backend/Page/Component` with their README "Permissions" steps done: `BaseControl.php` (`use WriteAccess`, `$template->writeAllowed`), `BaseTemplate.php` (`$writeAllowed`), the controls (grid actions `->setVisible($this->isWriteAllowed())`, the comment buttons of the article tab `n:if="$writeAllowed"`, the clickable comments state only with the write privilege) and the templates (`addButton: $writeAllowed`). These are made by `php netis/tools/preset-permission.php` (netis repository): run it when such a file changes in its package.
  - From project-gallery (made by the same tool): `Backend/Gallery/Component` (`GalleryControl` with `use WriteAccess` and `$template->writeAllowed`, `GalleryTemplate::$writeAllowed`, `Gallery.latte` with the upload form and the delete buttons only when `$writeAllowed`) and `Backend/Gallery/GalleryPresenter.php` (`readOnlySignals()` returns `preview`, so the image preview is checked as `gallery-read`; `BasePresenter::isReadOnlySignal()` asks those lists through the `isReadOnlyByLists` alias of the trait method besides `DataGrid::isReadOnlySignal()`).
- Installer (no copies): `Install/permission.neon` registers `Install/PermissionMigrations` (tag `install.migrations`: the `migrations-permission/` of all installed packages, by migration-priority, run by the web installer after the package migrations and before the project ones, recorded under their package) and `Install/AdminRole` (tag `install.account`: the new administrator gets role 1 in `users_roles`). The installer container of project-install loads the neons and classes of `app/Presentation/Install/`; the main application excludes that directory.
- Menu: `Backend/Admin/99.menu.neon` (loaded last) adds `allowAny` to the keyed items of project-backend-ui (`dashboard`) and project-preset (`settings`, `blog`, `page`, `gallery`); Nette merges the keyed maps, so their files are not copied. `Backend/Permission/3.menu.neon` is the item of project-permission (it ships no menu file): one link `Permission:` with `allowAny` (roles-read, users-read), no sub-items; the roles of users link to the roles like the blog articles to the categories. A new item of project-preset needs its check added in `99.menu.neon`.
- No `PermissionProvider` classes here: they are generated by the packages' `bin/create-*-permission` scripts, run by `drago-commands` (project-tools), which `create-netis` (netis-cms/netis) runs and adds to the project's Composer scripts.

## Dependencies

Composer constraints describe the package contract. Before changing integration behavior, inspect the source and the actual call sites in this package; do not infer runtime usage solely from dependency declarations.
Development dependencies include phpstan/phpstan-nette (^2.0).

## Essential Commands

```bash
composer phpstan                # phpstan analyse
```

## Tests

This repository does not currently define a test script in `composer.json` or contain a `tests/` directory.
Do not claim behavior is covered by tests unless you find and run a relevant suite. When adding a test suite, follow the package’s existing Nette Tester setup if one is present and add an explicit Composer script.

## Conventions & Rules

- Preserve backward compatibility unless the task explicitly permits a breaking change. Check callers and public signatures before changing behavior.
- Follow the existing PHP style: strict types, explicit parameter and return types, tabs for indentation, and single-quoted strings where practical. Match nearby code when a convention is unclear.
- Keep package-specific configuration, resources, and migrations in their established directories. Do not edit generated or consuming-project files without confirming they are part of this repository’s intended output.
- Add or update focused tests for behavioral changes when this package has a runnable test setup. Do not invent test commands; use only the scripts and configuration present here.
- Update README examples and Composer metadata when a public API or supported requirement changes.
