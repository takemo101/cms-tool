<?php

namespace Takemo101\CmsTool\Http\Controller\Admin;

use CmsTool\Support\Validation\HttpValidationErrorException;
use CmsTool\Theme\ActiveTheme;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Takemo101\CmsTool\Http\Renderer\UpdatedJsonRenderer;
use Takemo101\CmsTool\Http\Validator\ThemeCustomizationValidator;
use Takemo101\CmsTool\Http\ViewModel\ThemeCustomizationEditPage;
use Takemo101\CmsTool\UseCase\Shared\Exception\NotFoundDataException;
use Takemo101\CmsTool\UseCase\Theme\Handler\ApplyThemeCustomizationHandler;
use Takemo101\CmsTool\UseCase\Theme\Handler\CacheThemeCustomizationHandler;
use Takemo101\CmsTool\UseCase\Theme\Support\ThemeCustomizationTemporaryCache;

class ThemeCustomizationController
{
    /**
     * constructor
     *
     * @param ActiveTheme $activeTheme
     */
    public function __construct(
        private readonly ActiveTheme $activeTheme,
    ) {
        //
    }

    /**
     * Theme customization edit page
     *
     * @param ThemeCustomizationTemporaryCache $cache
     * @return View
     */
    public function edit(
        ThemeCustomizationTemporaryCache $cache,
    ): View {
        // Clear the cache data before displaying the edit page to prevent the preview page from reflecting the cached data.
        $cache->clear($this->activeTheme->id);

        return view(
            'cms-tool::theme.customization',
            new ThemeCustomizationEditPage($this->activeTheme),
        );
    }

    /**
     * Save the theme customization data temporarily.
     *
     * @param ServerRequestInterface $request
     * @param ThemeCustomizationValidator $validator
     * @param CacheThemeCustomizationHandler $handler
     */
    public function cache(
        ServerRequestInterface $request,
        ThemeCustomizationValidator $validator,
        CacheThemeCustomizationHandler $handler,
    ): UpdatedJsonRenderer {
        $errors = $validator->validate(
            body: (array) $request->getParsedBody(),
            schema: $this->activeTheme->meta->schema,
            isStrict: false,
        );

        if ($errors->count()) {
            throw new HttpValidationErrorException(
                errors: $errors,
                request: $request,
            );
        }

        try {
            $handler->handle(
                id: $this->activeTheme->id,
                data: (array) $request->getParsedBody(),
            );
        } catch (NotFoundDataException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }

        return new UpdatedJsonRenderer("Updated successfully for {$this->activeTheme->id} theme.");
    }

    /**
     * Apply the theme customization data to the theme file.
     *
     * @param ServerRequestInterface $request
     * @param ThemeCustomizationValidator $validator
     * @param ApplyThemeCustomizationHandler $handler
     * @return UpdatedJsonRenderer
     */
    public function apply(
        ServerRequestInterface $request,
        ThemeCustomizationValidator $validator,
        ApplyThemeCustomizationHandler $handler,
    ): UpdatedJsonRenderer {

        $errors = $validator->validate(
            body: (array) $request->getParsedBody(),
            schema: $this->activeTheme->meta->schema,
            isStrict: true,
        );

        if ($errors->count()) {
            throw new HttpValidationErrorException(
                errors: $errors,
                request: $request,
            );
        }

        try {
            $handler->handle(
                id: $this->activeTheme->id,
                data: (array) $request->getParsedBody(),
            );
        } catch (NotFoundDataException $e) {
            throw new HttpNotFoundException($request, $e->getMessage(), $e);
        }

        return new UpdatedJsonRenderer("Applied successfully for {$this->activeTheme->id} theme.");
    }
}
