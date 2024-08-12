<?php

use CmsTool\Support\Feed\Feed;
use CmsTool\Support\Feed\FeedItem;
use CmsTool\Support\Feed\Rss2FeedGenerator;
use CmsTool\Support\Feed\FeedOutput;
use CmsTool\Support\Feed\DOMDocumentHelper;
use CmsTool\Support\Feed\FeedItems;
use Mockery as m;

describe('Rss2FeedGenerator', function () {
    it('generates RSS 2.0 feed XML', function () {
        // Create a mock DOMDocumentHelper
        $helper = m::mock(DOMDocumentHelper::class);
        $helper->shouldReceive('createElement')->andReturnUsing(
            function (DOMDocument $xml, string $name, string $value = '') {
                return $xml->createElement($name, $value);
            }
        );
        $helper->shouldReceive('createElementWithCDATA')->andReturnUsing(function ($xml, $name, $value) {
            $element = $xml->createElement($name);
            $element->appendChild($xml->createCDATASection($value));
            return $element;
        });

        // Create a mock Feed
        $feed = new Feed(
            title: 'Example Feed',
            description: 'This is an example feed',
            link: 'https://example.com/feed',
            updated: new DateTime('2022-01-01 00:00:00', new DateTimeZone('UTC')),
            language: 'en',
            items: new FeedItems(
                new FeedItem(
                    title: 'Item 1',
                    link: 'https://example.com/item1',
                    published: new DateTime('2022-01-01 12:00:00', new DateTimeZone('UTC')),
                    content: 'Item 1 content',
                ),
                new FeedItem(
                    title: 'Item 2',
                    link: 'https://example.com/item2',
                    published: new DateTime('2022-01-02 12:00:00', new DateTimeZone('UTC')),
                    content: 'Item 2 content',
                ),
            ),
        );

        // Create an instance of Rss2FeedGenerator
        $generator = new Rss2FeedGenerator($helper);

        // Generate the feed
        $output = $generator->generate($feed);

        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>\n<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom" xml:lang="en"><channel><atom:link href="https://example.com/feed" rel="self" type="application/rss+xml"/><title><![CDATA[Example Feed]]></title><link><![CDATA[https://example.com/feed]]></link><description><![CDATA[This is an example feed]]></description><lastBuildDate>Sat, 01 Jan 2022 00:00:00 +0000</lastBuildDate><language>en</language><copyright><![CDATA[Example Feed]]></copyright><generator>CmsTool Feed Generator</generator><item><title><![CDATA[Item 1]]></title><link><![CDATA[https://example.com/item1]]></link><pubDate>Sat, 01 Jan 2022 12:00:00 +0000</pubDate><description><![CDATA[Item 1 content]]></description><guid isPermaLink="true">https://example.com/item1</guid></item><item><title><![CDATA[Item 2]]></title><link><![CDATA[https://example.com/item2]]></link><pubDate>Sun, 02 Jan 2022 12:00:00 +0000</pubDate><description><![CDATA[Item 2 content]]></description><guid isPermaLink="true">https://example.com/item2</guid></item></channel></rss>\n
XML;

        // Assert the generated output
        expect($output)->toBeInstanceOf(FeedOutput::class);
        expect($output->output)->toBe($expected);
        expect($output->charset)->toBe('UTF-8');
        expect($output->contentType)->toBe('application/xml');

        // Assert the generated output is valid XML
        $xml = new DOMDocument();
        expect($xml->loadXML($output->output))->toBeTrue();
    });
})->group('Rss2FeedGenerator', 'feed');
