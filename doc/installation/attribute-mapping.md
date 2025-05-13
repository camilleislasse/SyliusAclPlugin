# Attribute-mapping

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
                        type: attribute
```

Extend entities with parameters and methods using attributes and traits:

- `AdminUser` entity:

`src/Entity/User/AdminUser.php`


```php
<?php

declare(strict_types=1);

namespace App\Entity\User;

use BitBag\SyliusAclPlugin\Entity\Role;
use BitBag\SyliusAclPlugin\Entity\RoleInterface;
use BitBag\SyliusAclPlugin\Model\RoleableTrait;
use BitBag\SyliusAclPlugin\Model\ToggleablePermissionCheckerTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\AdminUser as BaseAdminUser;
use Symfony\Component\Validator\Constraints\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_admin_user')]
class AdminUser extends BaseAdminUser
{
    use ToggleablePermissionCheckerTrait;
    use RoleableTrait;

    #[ORM\Column(type: 'boolean')]
    protected $enablePermissionChecker = false;

    /** @var Collection|RoleInterface[] */
    #[ORM\ManyToMany(targetEntity: Role::class)]
    #[ORM\JoinTable(name: 'bitbag_admin_users_roles_resources')]
    #[ORM\JoinColumn(name: 'admin_user_id', referencedColumnName: 'id', unique: false)]
    #[ORM\InverseJoinColumn(name: 'role_resource_id', referencedColumnName: 'id', unique: false)]
    protected $rolesResources;

    public function __construct()
    {
        parent::__construct();

        $this->rolesResources = new ArrayCollection();
    }
}
```
