<?php

namespace Takemo101\CmsTool\Http\ViewModel;

use CmsTool\Theme\Theme;
use CmsTool\View\Contract\TemplateRenderer;

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
     * @return string
     */
    public function content(
        TemplateRenderer $renderer,
    ): string {
        return $renderer->renderString(
            template: $this->theme->setting->content,
            data: [
                'theme' => $this->theme,
            ],
        );
    }
}
