<?php

namespace Takemo101\CmsTool\Http\ViewModel;

use CmsTool\Theme\Support\ThemeReadmeReader;
use CmsTool\Theme\Theme;
use Takemo101\CmsTool\Infra\Markdown\MarkdownToHtml;

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
     * @param ThemeReadmeReader $reader
     * @param MarkdownToHtml $toHtml
     * @return string|null
     */
    public function readme(
        ThemeReadmeReader $reader,
        MarkdownToHtml $toHtml,
    ): ?string {
        $readme = $reader->get($this->theme);

        return $readme
            ? $toHtml($readme)
            : null;
    }
}
