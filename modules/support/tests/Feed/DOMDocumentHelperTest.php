<?php

use CmsTool\Support\Feed\DOMDocumentHelper;

describe(
    'DOMDocumentHelper',
    function () {

        beforeEach(function () {
            $this->document = new DOMDocument();
            $this->helper = new DOMDocumentHelper();
        });

        it('should create a DOMElement', function () {
            $name = 'test';
            $value = 'value';

            $element = $this->helper->createElement($this->document, $name, $value);

            expect($element)->toBeInstanceOf(DOMElement::class);
            expect($element->nodeName)->toBe($name);
            expect($element->nodeValue)->toBe($value);
        });

        it('should throw a RuntimeException if failed to create an element', function () {
            $name = 'test';
            $value = 'value';

            $document = Mockery::mock(DOMDocument::class);
            $document->shouldReceive('createElement')
                ->once()
                ->with($name, $value)
                ->andReturn(false);

            $helper = new DOMDocumentHelper();

            expect(fn() => $helper->createElement($document, $name, $value))
                ->toThrow(RuntimeException::class);
        });

        it('should create a CDATA section', function () {
            $data = 'Some <CDATA> data';

            $cdata = $this->helper->createCDATA($this->document, $data);

            expect($cdata)->toBeInstanceOf(DOMCdataSection::class);
            expect($cdata->data)->toBe('Some <CDATA> data');
        });

        it('should throw a RuntimeException if failed to create a CDATA section', function () {
            $data = 'Some <CDATA> data';

            $document = Mockery::mock(DOMDocument::class);
            $document->shouldReceive('createCDATASection')
                ->once()
                ->with($data)
                ->andReturn(false);

            $helper = new DOMDocumentHelper();

            expect(fn() => $helper->createCDATA($document, $data))
                ->toThrow(RuntimeException::class);
        });

        it('should create a DOMElement with a CDATA section', function (
            string $data,
            string $expected
        ) {
            $name = 'test';

            $element = $this->helper->createElementWithCDATA($this->document, $name, $data);

            expect($element)->toBeInstanceOf(DOMElement::class);
            expect($element->nodeName)->toBe($name);

            $cdata = $element->firstChild;
            expect($cdata)->toBeInstanceOf(DOMCdataSection::class);
            expect($cdata->data)->toBe($expected);
        })->with([
            ['Some <![CDATA[ data', 'Some &lt;![CDATA[ data'],
            ['Some ]]> data', 'Some ]]&gt; data'],
            ['Some <![CDATA[ ]]> data', 'Some &lt;![CDATA[ ]]&gt; data'],
            ['Some <CDATA> data', 'Some <CDATA> data'],
        ]);
    }
)->group('DOMDocumentHelper', 'feed');
