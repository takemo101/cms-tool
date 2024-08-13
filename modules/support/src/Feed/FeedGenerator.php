<?php

namespace CmsTool\Support\Feed;

/**
 * Interface for generating feed information.
 */
interface FeedGenerator
{
    /**
     * @var string The name of the feed generator tool.
     */
    public const GeneratorName = 'CmsTool Feed Generator';

    /**
     * Generates feed information.
     *
     * @param Feed $feed
     * @return string The feed XML output.
     */
    public function generate(Feed $feed): string;

    /**
     * Gets the feed output meta information.
     *
     * @return FeedOutputMeta
     */
    public function getOutputMeta(): FeedOutputMeta;
}
