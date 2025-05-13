<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusAclPlugin\Cache\Warmer;

use BitBag\SyliusAclPlugin\Cache\Warmer\PrivilegesCacheWarmer;
use BitBag\SyliusAclPlugin\Privilege\PrivilegeInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

final class PrivilegesCacheWarmerSpec extends ObjectBehavior
{
    public function let(FilesystemAdapter $filesystemCache): void
    {
        $this->beConstructedWith($filesystemCache);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PrivilegesCacheWarmer::class);
    }

    public function it_implements_cache_clearer_interface(): void
    {
        $this->shouldImplement(CacheWarmerInterface::class);
    }

    public function it_deletes_privileges_cache_clearer_keys(FilesystemAdapter $filesystemCache): void
    {
        $filesystemCache->deleteItems(PrivilegeInterface::CACHE_CLEARER_KEYS)->shouldBeCalled();

        $this->warmUp('');
    }

    public function it_is_optional_return_false(): void
    {
        $this->isOptional(null)->shouldReturn(false);
    }
}
