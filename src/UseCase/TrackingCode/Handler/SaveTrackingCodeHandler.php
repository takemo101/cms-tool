<?php

namespace Takemo101\CmsTool\UseCase\TrackingCode\Handler;

use Takemo101\CmsTool\Domain\Tracking\TrackingCode;
use Takemo101\CmsTool\Domain\Tracking\TrackingCodeRepository;

class SaveTrackingCodeHandler
{
    /**
     * constructor
     *
     * @param TrackingCodeRepository $repository
     */
    public function __construct(
        private TrackingCodeRepository $repository,
    ) {
        //
    }

    /**
     * Execute the processing the processing
     *
     * @param SaveTrackingCodeCommand $command
     * @return TrackingCode
     */
    public function handle(SaveTrackingCodeCommand $command): TrackingCode
    {

        $trackingCode = new TrackingCode(
            head: $command->head,
            body: $command->body,
            footer: $command->footer,
        );

        $this->repository->save($trackingCode);

        return $trackingCode;
    }
}
