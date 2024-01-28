<?php

namespace Takemo101\CmsTool\Infra\Markdown;

use DI\Attribute\Inject;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\MarkdownConverter;

class MarkdownToHtml
{
    /**
     * constructor
     *
     * @param MarkdownConverter $converter
     */
    public function __construct(
        #[Inject(CommonMarkConverter::class)]
        private MarkdownConverter $converter
    ) {
        //
    }

    /**
     * Convert markdown to html
     *
     * @param string $markdown
     * @return string
     */
    public function __invoke(string $markdown): string
    {
        return $this->converter->convert($markdown);
    }
}
