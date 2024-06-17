<?php

use Slim\Interfaces\RouteParserInterface;
use Takemo101\Chubby\Http\Renderer\RedirectRenderer;
use Takemo101\Chubby\Http\Renderer\ResponseRenderer;
use Takemo101\CmsTool\Support\RedirectRendererFactory;
use Takemo101\CmsTool\Support\Toast\ToastRenderer;
use Takemo101\CmsTool\Support\Toast\ToastStyle;

if (!function_exists('route')) {
    /**
     * Obtain a URI path from the named route.
     *
     * @param string $name
     * @param array<string,mixed> $data
     * @param array<string,mixed> $query
     * @return string
     */
    function route(string $name, array $data = [], array $query = []): string
    {
        /** @var RouteParserInterface */
        $route = container()->get(RouteParserInterface::class);

        return $route->urlFor($name, $data, $query);
    }
}

if (!function_exists('redirect')) {
    /**
     * Create a redirect renderer
     *
     * @return RedirectRendererFactory|RedirectRenderer
     */
    function redirect(?string $url = null): RedirectRendererFactory|RedirectRenderer
    {
        $factory = new RedirectRendererFactory();

        return $url
            ? $factory->url($url)
            : $factory;
    }
}

if (!function_exists('e')) {
    /**
     * Encode special characters in a string.
     *
     * @param string $value
     * @param bool $doubleEncode
     * @return string
     */
    function e($value, $doubleEncode = true)
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'utf-8', $doubleEncode);
    }
}

if (!function_exists('toast')) {
    /**
     * Create a toast renderer
     *
     * @template T of ResponseRenderer
     *
     * @param T $response
     * @param string|null $message
     * @return ToastRenderer<T>
     */
    function toast(
        ResponseRenderer $response,
        ToastStyle $style = ToastStyle::Success,
        ?string $message = null,
    ): ToastRenderer {
        return new ToastRenderer(
            response: $response,
            style: $style,
            message: $message,
        );
    }
}
