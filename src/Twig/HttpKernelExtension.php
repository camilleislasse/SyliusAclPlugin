<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusAclPlugin\Twig;

use Symfony\Bridge\Twig\Extension\HttpKernelRuntime;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class HttpKernelExtension extends AbstractExtension
{
    public function __construct(
        protected HttpKernelRuntime $httpKernelRuntime,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('render', [$this, 'renderFragment'], ['is_safe' => ['html']]),
            new TwigFunction('render_*', [HttpKernelRuntime::class, 'renderFragmentStrategy'], ['is_safe' => ['html']]),
            new TwigFunction('controller', [static::class, 'controller']),
        ];
    }

    public function renderFragment(string|ControllerReference $uri, array $options = []): ?string
    {
        if (RoutingExtension::ACCESS_DENIED !== $uri) {
            return $this->httpKernelRuntime->renderFragment($uri, $options);
        }

        return '';
    }

    public static function controller(
        string $controller,
        array $attributes = [],
        array $query = [],
    ): ControllerReference {
        return new ControllerReference($controller, $attributes, $query);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'http_kernel';
    }
}
