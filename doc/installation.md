# Installation

## Overview:
GENERAL
- [Requirements](#requirements)
- [Configuring the repository](#configuring-the-repository)
- [Composer](#composer)
- [Basic configuration](#basic-configuration)
--- 
BACKEND
- [Entities](#entities)
    - [Attribute mapping](#attribute-mapping)
    - [XML mapping](#xml-mapping)
---
FRONTEND
- [Templates](#templates)
---
ADDITIONAL
- [Known Issues](#known-issues)
---

## Requirements:
We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version         |
|---------------|-----------------|
| PHP           | \>8.0           |
| sylius/sylius | 1.12.x - 1.13.x |
| MySQL         | \>= 5.7         |
| NodeJS        | \>= 18.x        |

## Configuring the repository
Add a package to your private repository and add the repository to `composer.json`:

```json
{
    "repositories": {
        "private-packagist": {
            "type": "composer",
            "url": "<your URL to private repository>"
        }
    }
}
```

Additionally, you'll need to run the command below to setup your access token:
```bash
composer config --auth http-basic.bitbagcommerce.repo.packagist.com token <your TOKEN to private repository>
```
This command will create in your project directory an auth.json file as below:

```json
{
    "http-basic": {
        "bitbagcommerce.repo.packagist.com": {
            "username": "token",
            "password": "<your TOKEN to private repository>"
        }
    }
}
```
After these steps, you can proceed with the plugin installation process.


## Composer:
```bash
composer require bitbag/acl-plugin
```

## Basic configuration:
Add plugin dependencies to your `config/bundles.php` file:

```php
# config/bundles.php

return [
    ...
    BitBag\SyliusAclPlugin\BitBagSyliusAclPlugin::class => ['all'=>true]
];
```

Import required config in your `config/packages/_sylius.yaml` file:

```yaml
# config/packages/_sylius.yaml

imports:
    #...
    - { resource: "@BitBagSyliusAclPlugin/Resources/config/config.yml" }
```

...and add authorization checker for sylius resource:
```yaml
# config/packages/_sylius.yaml
...

sylius_resource:
    authorization_checker: bitbag_sylius_acl_plugin.authorization_checker.resource
```

Overwrite services in your `twig.yml` file:
```yaml
# config/packages/twig.yaml
...

services:
    twig.extension.httpkernel:
        class: BitBag\SyliusAclPlugin\Twig\HttpKernelExtension
        arguments:
            - "@twig.runtime.httpkernel"

    twig.extension.routing:
        class: BitBag\SyliusAclPlugin\Twig\RoutingExtension
        public: false
        arguments:
            - "@router.default"
            - "@bitbag_sylius_acl_plugin.resolver.admin_permission"
```

Add routing to your `config/routes.yaml` file:
```yaml
# config/routes.yaml

bitbag_sylius_acl_plugin:
    resource: "@BitBagSyliusAclPlugin/Resources/config/routing.yml"
```

## Entities
You can implement entity configuration by using both xml-mapping and attribute-mapping. Depending on your preference, choose either one or the other:
### Attribute mapping
- [Attribute mapping configuration](installation/attribute-mapping.md)
### XML mapping
- [XML mapping configuration](installation/xml-mapping.md)

### Update your database
First, please run legacy-versioned migrations by using command:
```bash
bin/console doctrine:migrations:migrate
```

After migration, please create a new diff migration and update database:
```bash
bin/console doctrine:migrations:diff
bin/console doctrine:migrations:migrate
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

## Templates
Copy required templates into correct directories in your project.

**AdminBundle** (`templates/bundles/SyliusAdminBundle`):
```
vendor/bitbag/acl-plugin/tests/Application/templates/bundles/SyliusAdminBundle/AdminUser/_form.html.twig
```

**UiBundle** (`templates/bundles/SyliusUiBundle`):
```
vendor/bitbag/acl-plugin/tests/Application/templates/bundles/SyliusUiBundle/Grid/_default.html.twig
```

### Install assets
```bash
bin/console assets:install
```

### Clear application cache by using command:
```bash
bin/console cache:clear
```
**Note:** If you are running it on production, add the `-e prod` flag to this command.

## Known issues
### Translations not displaying correctly
For incorrectly displayed translations, execute the command:
```bash
bin/console cache:clear
```
