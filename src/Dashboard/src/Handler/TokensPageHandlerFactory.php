<?php

declare(strict_types=1);

namespace Dashboard\Handler;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class TokensPageHandlerFactory
{
    public function __invoke(ContainerInterface $container) : TokensPageHandler
    {
        return new TokensPageHandler($container->get(TemplateRendererInterface::class));
    }
}
