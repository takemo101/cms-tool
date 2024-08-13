<?php

use Takemo101\CmsTool\Preset\Shared\HeaderTitle\HeaderLayer;
use Takemo101\CmsTool\Preset\Shared\HeaderTitle\HeaderLayers;
use Takemo101\CmsTool\Preset\Shared\HeaderTitle\HeaderTitle;
use Takemo101\CmsTool\Preset\Shared\HeaderTitle\HeaderTitleLevel;
use Takemo101\CmsTool\Preset\Shared\HeaderTitle\HeaderTitles;
use Takemo101\CmsTool\Preset\Shared\HeaderTitle\LayeredHeaderTitle;

describe(
    'HeaderTitles',
    function () {
        it(
            'should create a hierarchical structure of header titles',
            /**
             * @param HeaderTitle[] $data
             * @param HeaderLayers $expected
             */
            function (
                array $data,
                HeaderLayers $expected,
            ) {

                $titles = new HeaderTitles(...$data);

                $layers = $titles->layering();
                expect($layers)->toBeInstanceOf(HeaderLayers::class);
                expect($layers)->toEqual($expected);
            }
        )->with([
            fn () => [
                [
                    new HeaderTitle('1', 'Title 1', new HeaderTitleLevel(1)),
                    new HeaderTitle('2', 'Title 2', new HeaderTitleLevel(2)),
                    new HeaderTitle('3', 'Title 3', new HeaderTitleLevel(4)),
                    new HeaderTitle('4', 'Title 4', new HeaderTitleLevel(4)),
                    new HeaderTitle('5', 'Title 5', new HeaderTitleLevel(5)),
                    new HeaderTitle('6', 'Title 6', new HeaderTitleLevel(6)),
                    new HeaderTitle('7', 'Title 7', new HeaderTitleLevel(2)),
                    new HeaderTitle('8', 'Title 8', new HeaderTitleLevel(3)),
                ],
                new HeaderLayers(
                    new HeaderLayer(
                        new HeaderTitleLevel(1),
                        new HeaderLayers(
                            new HeaderLayer(
                                new HeaderTitleLevel(2),
                                new HeaderLayers(
                                    new HeaderLayer(
                                        new HeaderTitleLevel(3),
                                        new HeaderLayers(
                                            new HeaderLayer(
                                                new HeaderTitleLevel(4),
                                                new HeaderLayers(),
                                                new LayeredHeaderTitle(
                                                    '3',
                                                    'Title 3',
                                                ),
                                            ),
                                            new HeaderLayer(
                                                new HeaderTitleLevel(4),
                                                new HeaderLayers(
                                                    new HeaderLayer(
                                                        new HeaderTitleLevel(5),
                                                        new HeaderLayers(
                                                            new HeaderLayer(
                                                                new HeaderTitleLevel(6),
                                                                new HeaderLayers(),
                                                                new LayeredHeaderTitle(
                                                                    '6',
                                                                    'Title 6',
                                                                ),
                                                            ),
                                                        ),
                                                        new LayeredHeaderTitle(
                                                            '5',
                                                            'Title 5',
                                                        ),
                                                    ),
                                                ),
                                                new LayeredHeaderTitle(
                                                    '4',
                                                    'Title 4',
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                                new LayeredHeaderTitle(
                                    '2',
                                    'Title 2',
                                ),
                            ),
                            new HeaderLayer(
                                new HeaderTitleLevel(2),
                                new HeaderLayers(
                                    new HeaderLayer(
                                        new HeaderTitleLevel(3),
                                        new HeaderLayers(),
                                        new LayeredHeaderTitle(
                                            '8',
                                            'Title 8',
                                        ),
                                    ),
                                ),
                                new LayeredHeaderTitle(
                                    '7',
                                    'Title 7',
                                ),
                            ),
                        ),
                        new LayeredHeaderTitle(
                            '1',
                            'Title 1',
                        ),
                    ),
                )
            ],
            fn () => [
                [
                    new HeaderTitle('1', 'Title 1', new HeaderTitleLevel(1)),
                    new HeaderTitle('2', 'Title 2', new HeaderTitleLevel(2)),
                    new HeaderTitle('3', 'Title 3', new HeaderTitleLevel(3)),
                    new HeaderTitle('4', 'Title 4', new HeaderTitleLevel(4)),
                    new HeaderTitle('5', 'Title 5', new HeaderTitleLevel(5)),
                    new HeaderTitle('6', 'Title 6', new HeaderTitleLevel(6)),
                    new HeaderTitle('1', 'Title 1', new HeaderTitleLevel(1)),
                    new HeaderTitle('2', 'Title 2', new HeaderTitleLevel(2)),
                    new HeaderTitle('3', 'Title 3', new HeaderTitleLevel(3)),
                    new HeaderTitle('4', 'Title 4', new HeaderTitleLevel(4)),
                    new HeaderTitle('5', 'Title 5', new HeaderTitleLevel(5)),
                    new HeaderTitle('6', 'Title 6', new HeaderTitleLevel(6)),
                ],
                new HeaderLayers(
                    new HeaderLayer(
                        new HeaderTitleLevel(1),
                        new HeaderLayers(
                            new HeaderLayer(
                                new HeaderTitleLevel(2),
                                new HeaderLayers(
                                    new HeaderLayer(
                                        new HeaderTitleLevel(3),
                                        new HeaderLayers(
                                            new HeaderLayer(
                                                new HeaderTitleLevel(4),
                                                new HeaderLayers(
                                                    new HeaderLayer(
                                                        new HeaderTitleLevel(5),
                                                        new HeaderLayers(
                                                            new HeaderLayer(
                                                                new HeaderTitleLevel(6),
                                                                new HeaderLayers(),
                                                                new LayeredHeaderTitle(
                                                                    '6',
                                                                    'Title 6',
                                                                ),
                                                            ),
                                                        ),
                                                        new LayeredHeaderTitle(
                                                            '5',
                                                            'Title 5',
                                                        ),
                                                    ),
                                                ),
                                                new LayeredHeaderTitle(
                                                    '4',
                                                    'Title 4',
                                                ),
                                            ),
                                        ),
                                        new LayeredHeaderTitle(
                                            '3',
                                            'Title 3',
                                        ),
                                    ),
                                ),
                                new LayeredHeaderTitle(
                                    '2',
                                    'Title 2',
                                ),
                            ),
                        ),
                        new LayeredHeaderTitle(
                            '1',
                            'Title 1',
                        ),
                    ),
                    new HeaderLayer(
                        new HeaderTitleLevel(1),
                        new HeaderLayers(
                            new HeaderLayer(
                                new HeaderTitleLevel(2),
                                new HeaderLayers(
                                    new HeaderLayer(
                                        new HeaderTitleLevel(3),
                                        new HeaderLayers(
                                            new HeaderLayer(
                                                new HeaderTitleLevel(4),
                                                new HeaderLayers(
                                                    new HeaderLayer(
                                                        new HeaderTitleLevel(5),
                                                        new HeaderLayers(
                                                            new HeaderLayer(
                                                                new HeaderTitleLevel(6),
                                                                new HeaderLayers(),
                                                                new LayeredHeaderTitle(
                                                                    '6',
                                                                    'Title 6',
                                                                ),
                                                            ),
                                                        ),
                                                        new LayeredHeaderTitle(
                                                            '5',
                                                            'Title 5',
                                                        ),
                                                    ),
                                                ),
                                                new LayeredHeaderTitle(
                                                    '4',
                                                    'Title 4',
                                                ),
                                            ),
                                        ),
                                        new LayeredHeaderTitle(
                                            '3',
                                            'Title 3',
                                        ),
                                    ),
                                ),
                                new LayeredHeaderTitle(
                                    '2',
                                    'Title 2',
                                ),
                            ),
                        ),
                        new LayeredHeaderTitle(
                            '1',
                            'Title 1',
                        ),
                    ),
                )
            ],
            fn () => [
                [
                    new HeaderTitle('3', 'Title 1', new HeaderTitleLevel(3)),
                    new HeaderTitle('4', 'Title 2', new HeaderTitleLevel(4)),
                    new HeaderTitle('5', 'Title 3', new HeaderTitleLevel(5)),
                ],
                new HeaderLayers(
                    new HeaderLayer(
                        new HeaderTitleLevel(1),
                        new HeaderLayers(
                            new HeaderLayer(
                                new HeaderTitleLevel(2),
                                new HeaderLayers(
                                    new HeaderLayer(
                                        new HeaderTitleLevel(3),
                                        new HeaderLayers(
                                            new HeaderLayer(
                                                new HeaderTitleLevel(4),
                                                new HeaderLayers(
                                                    new HeaderLayer(
                                                        new HeaderTitleLevel(5),
                                                        new HeaderLayers(),
                                                        new LayeredHeaderTitle(
                                                            '5',
                                                            'Title 3',
                                                        ),
                                                    ),
                                                ),
                                                new LayeredHeaderTitle(
                                                    '4',
                                                    'Title 2',
                                                ),
                                            ),
                                        ),
                                        new LayeredHeaderTitle(
                                            '3',
                                            'Title 1',
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                )
            ],
        ]);
    },
)->group('HeaderTitles', 'preset');
