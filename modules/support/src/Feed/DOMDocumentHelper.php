<?php

namespace CmsTool\Support\Feed;

use DOMCdataSection;
use DOMDocument;
use DOMElement;
use RuntimeException;

/**
 * Helper class for DOMDocument.
 */
class DOMDocumentHelper
{
    /**
     * Create a DOMElement.
     *
     * @param DOMDocument $document
     * @param string $name
     * @param string $value
     * @return DOMElement
     */
    public function createElement(DOMDocument $document, string $name, string $value = ''): DOMElement
    {
        $element = $document->createElement($name, $value);

        if (!($element instanceof DOMElement)) {
            throw new RuntimeException('Failed to create an element.');
        }

        return $element;
    }

    /**
     * Create a CDATA section.
     *
     * @param DOMDocument $document
     * @param string $data
     * @return DOMCdataSection
     */
    public function createCDATA(DOMDocument $document, string $data): DOMCdataSection
    {
        $replaced = str_replace(
            [
                '<![CDATA[',
                ']]>',
            ],
            [
                '&lt;![CDATA[',
                ']]&gt;',
            ],
            $data
        );

        $cdata = $document->createCDATASection($replaced);

        if (!($cdata instanceof DOMCdataSection)) {
            throw new RuntimeException('Failed to create a CDATA section.');
        }

        return $cdata;
    }

    /**
     * Create a DOMElement with a CDATA section.
     *
     * @param DOMDocument $document
     * @param string $name
     * @param string $data
     * @return DOMElement
     */
    public function createElementWithCDATA(DOMDocument $document, string $name, string $data): DOMElement
    {
        $element = $this->createElement($document, $name);
        $cdata = $this->createCDATA($document, $data);

        $element->appendChild($cdata);

        return $element;
    }
}
