<?php

namespace Takemo101\CmsTool\Http\ViewModel;

use CmsTool\Theme\Theme;
use CmsTool\View\Contract\TemplateRenderer;
use CmsTool\View\ViewCreator;

class ThemeDetailPage extends ViewModel
{
    /**
     * constructor
     *
     * @param Theme $theme
     */
    public function __construct(
        public Theme $theme,
    ) {
        //
    }

    /**
     * @param TemplateRenderer $renderer
     * @param ViewCreator $creator
     * @return string
     */
    public function content(
        TemplateRenderer $renderer,
        ViewCreator $creator,
    ): string {
        return $renderer->renderString(
            template: $this->theme->setting->content,
            data: [
                ...$creator->getShared(),
                'theme' => $this->theme,
            ],
        );
    }
}
