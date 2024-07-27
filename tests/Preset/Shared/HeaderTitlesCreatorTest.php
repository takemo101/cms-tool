<?php

use Takemo101\CmsTool\Preset\Shared\HeaderTitle\HeaderTitlesCreator;
use Takemo101\CmsTool\Preset\Shared\HeaderTitle\HeaderTitles;

describe(
    'HeaderTitlesCreator',
    function () {
        it('creates header titles from HTML content', function () {
            $creator = new HeaderTitlesCreator();

            $content = <<<HTML
                <h1 id="1">Title 1</h1>
                <h2 id="2">Title 2</h2>
                <div>
                    <h4 id="3">Title 3</h4>
                    <h4 id="4">Title 4</h4>
                    <div>
                        <h5 id="5">Title 5</h5>
                        <div>
                            <h6 id="6">Title 6</h6>
                        </div>
                    </div>
                </div>
                <h2 id="7">Title 7</h2>
                <h3 id="8">Title 8</h3>
            HTML;

            $dom = new DOMDocument();

            $elements = array_map(
                function ($id, $level, $title) use ($dom) {

                    $element = $dom->createElement("h{$level}", $title);

                    $element->setAttribute('id', (string)$id);

                    return $element;
                },
                [1, 2, 3, 4, 5, 6, 7, 8],
                [1, 2, 4, 4, 5, 6, 2, 3],
                ['Title 1', 'Title 2', 'Title 3', 'Title 4', 'Title 5', 'Title 6', 'Title 7', 'Title 8'],
            );

            $expectedTitles = HeaderTitles::fromHTagDOMElements(...$elements);

            $titles = $creator->create($content);

            expect($titles)->toEqual($expectedTitles);
        });

        it('returns an empty HeaderTitles object if no header tags are found', function () {
            $creator = new HeaderTitlesCreator();

            $content = '<p>Some content without header tags</p>';
            $expectedTitles = new HeaderTitles();

            $titles = $creator->create($content);

            expect($titles)->toEqual($expectedTitles);
        });
    }
)->group('HeaderTitlesCreator', 'preset');
