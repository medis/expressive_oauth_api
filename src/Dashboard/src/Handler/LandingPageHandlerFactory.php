<?php

declare(strict_types=1);

namespace Dashboard\Handler;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class LandingPageHandlerFactory
{
    public function __invoke(ContainerInterface $container) : LandingPageHandler
    {
        return new LandingPageHandler($container->get(TemplateRendererInterface::class));
    }
}
