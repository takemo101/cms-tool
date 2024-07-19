<?php

use Psr\Http\Message\ServerRequestInterface;
use Takemo101\CmsTool\Support\Htmx\HtmxRequest;
use Mockery as m;

describe(
    'HtmxRequest',
    function () {
        beforeEach(function () {
            $this->request = m::mock(ServerRequestInterface::class);
        });

        it('should return true if the request is from Htmx', function () {
            $this->request->shouldReceive('getHeaderLine')
                ->with('HX-Request')
                ->andReturn('true');

            $htmxRequest = new HtmxRequest($this->request);
            $result = $htmxRequest->isHtmx();

            expect($result)->toBeTrue();
        });

        it('should return false if the request is not from Htmx', function () {
            $this->request->shouldReceive('getHeaderLine')
                ->with('HX-Request')
                ->andReturn('');

            $htmxRequest = new HtmxRequest($this->request);
            $result = $htmxRequest->isHtmx();

            expect($result)->toBeFalse();
        });

        it('should return the current URL if it exists', function () {
            $this->request->shouldReceive('getHeaderLine')
                ->with('HX-Current-URL')
                ->andReturn('https://example.com');

            $htmxRequest = new HtmxRequest($this->request);
            $result = $htmxRequest->getCurrentUrl();

            expect($result)->toBe('https://example.com');
        });

        it('should return false if the current URL does not exist', function () {
            $this->request->shouldReceive('getHeaderLine')
                ->with('HX-Current-URL')
                ->andReturn('');

            $this->request->shouldReceive('getHeaderLine')
                ->with('HX-Current-Url')
                ->andReturn('');

            $htmxRequest = new HtmxRequest($this->request);
            $result = $htmxRequest->getCurrentUrl();

            expect($result)->toBeFalse();
        });
    }
)->group('HtmxRequest', 'support');
