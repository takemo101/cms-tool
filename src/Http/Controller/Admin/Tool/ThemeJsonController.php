<?php

namespace Takemo101\CmsTool\Http\Controller\Admin\Tool;

use CmsTool\Session\Csrf\CsrfGuard;
use CmsTool\Support\JsonAccess\JsonAccessObjectCreator;
use CmsTool\View\View;
use Psr\Http\Message\ServerRequestInterface;
use Takemo101\CmsTool\Domain\Shared\IdCreator;
use Takemo101\CmsTool\Http\Renderer\RedirectBackRenderer;

class ThemeJsonController
{
    /**
     * Edit theme.json page
     *
     * @return View
     */
    public function edit(): View
    {
        return view('cms-tool::tool.theme-json');
    }

    /**
     * Generate theme.json
     *
     * @param ServerRequestInterface $request
     * @return RedirectBackRenderer
     */
    public function generate(
        ServerRequestInterface $request,
        JsonAccessObjectCreator $creator,
    ): RedirectBackRenderer {

        $body = $request->getParsedBody();

        $object = $creator->create(
            storage_path(
                'dump',
                IdCreator::random()->__toString() . '.json',
            ),
        );

        unset(
            $body[CsrfGuard::TokenNameKey],
            $body[CsrfGuard::TokenValueKey],
        );

        $json = [
            ...$body,
            'tags' => array_filter(explode(",", $body['tags'] ?? '') ?: []),
            'images' => array_filter(explode(",", $body['images'] ?? '') ?: []),
            'extension' => (object) [],
        ];

        $object->change($json);

        $object->save();

        return redirect()->back();
    }
}
