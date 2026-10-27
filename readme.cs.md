# Drago Project Preset Permission

Nadstavba [drago-ex/project-preset](https://github.com/drago-ex/project-preset), která do složeného projektu přidá oprávnění.

[![PHP version](https://badge.fury.io/ph/drago-ex%2Fproject-preset-permission.svg)](https://badge.fury.io/ph/drago-ex%2Fproject-preset-permission)
[![Coding Style](https://github.com/drago-ex/project-preset-permission/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/project-preset-permission/actions/workflows/coding-style.yml)

## Požadavky

- PHP >= 8.3, < 9
- [drago-ex/project-preset](https://github.com/drago-ex/project-preset)

## Instalace

```bash
composer require drago-ex/project-preset-permission
```

## Soubory kopírované do projektu

Soubory do projektu kopíruje [drago-ex/project-tools](https://github.com/drago-ex/project-tools)
podle nastavení v `extra.drago-tools` v `composer.json` tohoto balíčku.

## Co přidává

- [Oprávnění (ACL)](https://github.com/drago-ex/permission) a [správu rolí](https://github.com/drago-ex/project-permission) v administraci.
- Menu administrace zobrazuje jen položky, které uživatel smí otevřít.
- Tlačítka pro přidání, úpravu a mazání u blogu a statických stránek vidí jen role s privilegiem pro zápis.
- Role uživatelů se berou z databáze; webový instalátor dá administrátorovi roli `admin` a naimportuje
  privilegia balíčků.

## Providery oprávnění

Pravidla přístupu obsažených balíčků se vygenerují do projektu příkazem:

```bash
php vendor/bin/drago-commands
```
