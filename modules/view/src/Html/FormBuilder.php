<?php

namespace CmsTool\View\Html;

use CmsTool\View\Html\Filter\FormAppendFilter;
use CmsTool\View\Html\Filter\FormAppendFilters;
use CmsTool\View\Support\RouteParserProxy;
use DI\Attribute\Inject;
use Slim\Interfaces\RouteParserInterface;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class FormBuilder
{
    public const ElementName = 'form';

    /**
     * constructor
     *
     * @param RouteParserInterface $routeParser
     * @param ElementBuilder $builder
     * @param FormAppendFilter $appendFilter
     */
    public function __construct(
        #[Inject(RouteParserProxy::class)]
        private RouteParserInterface $routeParser,
        private ElementBuilder $builder = new ElementBuilder(),
        #[Inject(FormAppendFilters::class)]
        private FormAppendFilter $appendFilter = new FormAppendFilters(),
    ) {
        //
    }

    /**
     * Build form element open
     *
     * @param array<string,mixed> $attributes
     * @return string
     */
    public function buildOpen(array $attributes): string
    {
        /** @var string */
        $method = Arr::get($attributes, 'method', 'GET');
        $method = strtoupper($method);

        $attributes['method'] = $method;

        $method = $method !== 'GET'
            ? 'POST'
            : $method;

        /** @var string */
        $action = $this->getAction($attributes);

        Arr::forget($attributes, 'route');

        // @phpstan-ignore-next-line
        return $this->builder->buildOpen(self::ElementName, [
            ...$attributes,
            'method' => $method,
            'action' => $action,
        ]) . ($this->appendFilter->filter($attributes) ?? '');
    }

    /**
     * Build form element close
     *
     * @return string
     */
    public function buildClose(): string
    {
        return $this->builder->buildClose(self::ElementName);
    }

    /**
     * Get the form action from the attributes.
     *
     * @param array<string,mixed> $attributes
     * @return string
     */
    private function getAction(array $attributes): string
    {
        if (Arr::has($attributes, 'action')) {

            /** @var string */
            $action = Arr::get($attributes, 'action');

            return $action;
        }

        if (isset($attributes['route'])) {

            /** @var string|array{0:string,1:array<string,string>|null}|array{} */
            $options = Arr::get($attributes, 'route');

            return $this->getRouteAction($options);
        }

        return '/';
    }

    /**
     * Get the action for a "route" option.
     *
     * @param  string|array{0:string,1:array<string,string>|null}|array{} $options
     *
     * @return string
     * @throws InvalidArgumentException
     */
    private function getRouteAction(string|array $options): string
    {
        if (!is_array($options)) {
            return $this->routeParser->urlFor($options);
        }

        if (empty($options)) {
            throw new InvalidArgumentException(
                'Route data must not be empty.',
            );
        }

        /** @var string */
        $routeName = $options[0];

        /** @var array<string,string> */
        $data = $options[1] ?? [];

        if (!is_array($data)) {
            throw new InvalidArgumentException(
                'Route data must be an array.',
            );
        }

        return $this->routeParser->urlFor($routeName, $data);
    }
}
