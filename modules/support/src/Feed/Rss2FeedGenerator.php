<?php

namespace CmsTool\Support\Feed;

use DateTimeInterface;
use DOMDocument;
use DOMElement;
use RuntimeException;

/**
 * Class for generating RSS 2.0 feed XML.
 */
class Rss2FeedGenerator implements FeedGenerator
{
    public const Charset = 'UTF-8';

    public const ContentType = 'application/xml';

    public const MimeType = 'application/rss+xml';

    /**
     * Constructor
     *
     * @param DOMDocumentHelper $helper
     */
    public function __construct(
        private readonly DOMDocumentHelper $helper,
    ) {
        // Verify if the DOMDocument module is available
        if (!extension_loaded('dom')) {
            throw new RuntimeException('DOM extension is not loaded.');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function generate(Feed $feed): string
    {
        $xml = new DOMDocument(version: '1.0', encoding: self::Charset);

        $rssElement = $this->createHeader(xml: $xml, feed: $feed);

        $xml->appendChild($rssElement);

        $channelElement = $this->createChannel(xml: $xml, feed: $feed);

        $rssElement->appendChild($channelElement);

        foreach ($feed->items->items as $item) {
            $itemElement = $this->createItem(xml: $xml, item: $item);
            $channelElement->appendChild($itemElement);
        }

        $output = $xml->saveXML();

        if ($output === false) {
            throw new RuntimeException('Failed to save XML.');
        }

        return $output;
    }

    /**
     * Generate the header section of the feed.
     *
     * @param DOMDocument $xml
     * @param Feed $feed
     * @return DOMElement
     */
    private function createHeader(DOMDocument $xml, Feed $feed): DOMElement
    {
        $rssElement = $this->helper->createElement($xml, 'rss');

        $rssElement->setAttribute('version', '2.0');
        $rssElement->setAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
        $rssElement->setAttribute('xmlns:content', 'http://purl.org/rss/1.0/modules/content/');
        $rssElement->setAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');
        $rssElement->setAttribute('xml:lang', $feed->language);

        return $rssElement;
    }

    /**
     * Generate the channel section of the feed.
     *
     * @param DOMDocument $xml
     * @param Feed $feed
     * @return DOMElement
     */
    private function createChannel(DOMDocument $xml, Feed $feed): DOMElement
    {
        $channelElement = $this->helper->createElement($xml, 'channel');

        $atomLinkElement = $this->helper->createElement($xml, 'atom:link');

        $atomLinkElement->setAttribute('href', $feed->link);
        $atomLinkElement->setAttribute('rel', 'self');
        $atomLinkElement->setAttribute('type', 'application/rss+xml');
        $channelElement->appendChild($atomLinkElement);

        $channelElement->appendChild(
            $this->helper->createElementWithCDATA($xml, 'title', $feed->title),
        );
        $channelElement->appendChild(
            $this->helper->createElementWithCDATA($xml, 'link', $feed->link),
        );
        $channelElement->appendChild(
            $this->helper->createElementWithCDATA($xml, 'description', $feed->description),
        );
        $channelElement->appendChild($this->helper->createElement($xml, 'lastBuildDate', $this->datetimeToFormatString($feed->updated)));
        $channelElement->appendChild($this->helper->createElement($xml, 'language', $feed->language));

        if ($copyright = $feed->copyright) {
            $channelElement->appendChild(
                $this->helper->createElementWithCDATA($xml, 'copyright', $copyright),
            );
        }

        $channelElement->appendChild($this->helper->createElement($xml, 'generator', self::GeneratorName));

        return $channelElement;
    }

    /**
     * Generate the item section of the feed.
     *
     * @param DOMDocument $xml
     * @param FeedItem $item
     * @return DOMElement
     */
    private function createItem(DOMDocument $xml, FeedItem $item): DOMElement
    {
        $itemElement = $this->helper->createElement($xml, 'item');

        $itemElement->appendChild(
            $this->helper->createElementWithCDATA($xml, 'title', $item->title),
        );
        $itemElement->appendChild(
            $this->helper->createElementWithCDATA($xml, 'link', $item->link),
        );
        $itemElement->appendChild($this->helper->createElement($xml, 'pubDate', $this->datetimeToFormatString($item->published)));
        $itemElement->appendChild(
            $this->helper->createElementWithCDATA($xml, 'description', $item->content),
        );


        if ($author = $item->author) {
            $itemElement->appendChild(
                $this->helper->createElementWithCDATA($xml, 'dc:creator', $author->name),
            );

            $email = $author->email;

            $itemElement->appendChild(
                $this->helper->createElementWithCDATA(
                    $xml,
                    'author',
                    $email
                        ? "{$author->name}<{$email}>"
                        : $author->name,
                ),
            );
        }

        if ($enclosure = $item->enclosure) {
            $enclosureElement = $this->helper->createElement($xml, 'enclosure');

            $enclosureElement->setAttribute('url', $enclosure->url);
            $enclosureElement->setAttribute('length', (string) $enclosure->length);
            $enclosureElement->setAttribute('type', $enclosure->type);

            $itemElement->appendChild($enclosureElement);
        }

        foreach ($item->categories->categories as $category) {
            $categoryNode = $this->helper->createElement($xml, 'category', $category);

            $itemElement->appendChild($categoryNode);
        }

        $guidNode = $xml->createElement('guid', $item->guid);

        $guidNode->setAttribute('isPermaLink', $item->isGuidPermalink() ? 'true' : 'false');

        $itemElement->appendChild($guidNode);

        return $itemElement;
    }

    /**
     * Convert a DateTime object to RFC2822 format.
     *
     * @param DateTimeInterface $date
     * @return string
     */
    private function datetimeToFormatString(DateTimeInterface $date): string
    {
        return $date->format(DATE_RFC2822);
    }

    /**
     * {@inheritDoc}
     */
    public function getOutputMeta(): FeedOutputMeta
    {
        return new FeedOutputMeta(
            charset: self::Charset,
            contentType: self::ContentType,
            mimeType: self::MimeType,
        );
    }
}
