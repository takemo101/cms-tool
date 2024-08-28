<?php

namespace Takemo101\CmsTool\Http\Request\Admin;

use RuntimeException;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

readonly class UpdateThemeMetaJsonRequest
{
    public function __construct(
        #[NotBlank]
        public string $meta,
    ) {
        //
    }

    /**
     * @param ExecutionContextInterface $context
     * @return void
     */
    #[Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (json_decode($this->meta) === null) {
            $context->buildViolation('Invalid JSON format.')
                ->atPath('meta')
                ->addViolation();
        }
    }

    /**
     * @return array<string,mixed>
     * @throw RuntimeException
     */
    public function getMetaPayload(): array
    {
        /** @var array<string,mixed> */
        $data = json_decode($this->meta, true) ?? throw new RuntimeException('Invalid JSON format.');

        return $data;
    }
}
