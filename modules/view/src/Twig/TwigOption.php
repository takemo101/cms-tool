<?php

namespace CmsTool\View\Twig;

use DI\Attribute\Inject;
use InvalidArgumentException;
use Takemo101\Chubby\Contract\Arrayable;

/**
 * @implements Arrayable<string,mixed>
 */
final readonly class TwigOption implements Arrayable
{
    /**
     * constructor
     *
     * @param boolean $debug
     * @param string $charset
     * @param string $cache
     * @param boolean $autoReload
     * @param boolean $strictVariables
     * @param string $autoescape
     * @param integer $optimizations
     * @throws InvalidArgumentException
     */
    public function __construct(
        #[Inject('config.view.twig.environment.debug')]
        public bool $debug = false,
        #[Inject('config.view.twig.environment.charset')]
        public string $charset = 'utf-8',
        #[Inject('config.view.twig.environment.cache')]
        public string $cache = '',
        #[Inject('config.view.twig.environment.auto_reload')]
        public bool $autoReload = true,
        #[Inject('config.view.twig.environment.strict_variables')]
        public bool $strictVariables = false,
        #[Inject('config.view.twig.environment.autoescape')]
        public string $autoescape = 'html',
        #[Inject('config.view.twig.environment.optimizations')]
        public int $optimizations = -1,
    ) {
        if (empty($cache)) {
            throw new InvalidArgumentException('Cache path cannot be empty.');
        }
    }

    /**
     * Convert the object to its array representation.
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'debug' => $this->debug,
            'charset' => $this->charset,
            'cache' => $this->cache,
            'auto_reload' => $this->autoReload,
            'strict_variables' => $this->strictVariables,
            'autoescape' => $this->autoescape,
            'optimizations' => $this->optimizations,
        ];
    }
}
