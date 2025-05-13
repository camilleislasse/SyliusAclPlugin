# XML-mapping

Check the mapping settings in `config/packages/doctrine.yaml` and, if necessary, change them accordingly.
```yaml
doctrine:
    ...
    orm:
        entity_managers:
            default:
                ...
                mappings:
                    App:
                        ...
                        type: xml
                        dir: '%kernel.project_dir%/src/Resources/config/doctrine'
```

Extend entities with parameters and methods using attributes and traits:

- `AdminUser` entity:

`src/Entity/AdminUser.php`


```php
<?php

declare(strict_types=1);

namespace App\Entity;

use BitBag\SyliusAclPlugin\Model\RoleableTrait;
use BitBag\SyliusAclPlugin\Model\ToggleablePermissionCheckerTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Core\Model\AdminUser as BaseAdminUser;

class AdminUser extends BaseAdminUser
{
    use ToggleablePermissionCheckerTrait;
    use RoleableTrait;

    public function __construct()
    {
        parent::__construct();

        $this->rolesResources = new ArrayCollection();
    }
}
```

Define new Entity mapping inside `src/Resources/config/doctrine` directory.

- `AdminUser` entity:

`src/Resources/config/doctrine/AdminUser.orm.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>

<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                                      http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">

    <mapped-superclass name="App\Entity\AdminUser" table="sylius_admin_user">

        <field name="enablePermissionChecker" column="enable_permission_checker" type="boolean" />

        <many-to-many field="rolesResources" target-entity="BitBag\SyliusAclPlugin\Entity\Role">
            <join-table name="bitbag_admin_users_roles_resources">
                <join-columns>
                    <join-column name="admin_user_id" referenced-column-name="id" nullable="false" unique="false" on-delete="CASCADE" />
                </join-columns>
                <inverse-join-columns>
                    <join-column name="role_resource_id" referenced-column-name="id" nullable="false" unique="false" on-delete="CASCADE" />
                </inverse-join-columns>
            </join-table>
        </many-to-many>

    </mapped-superclass>

</doctrine-mapping>
```

Override `config/packages/_sylius.yaml` configuration:
```yaml
# config/_sylius.yaml

sylius_user:
    resources:
        admin:
            user:
                classes:
                    model: App\Entity\AdminUser
```
