<?php

namespace Takemo101\CmsTool\Support\Toast;

/**
 * Get toast information from the session
 */
class ToastSession
{
    public const InputErrorMessage = '入力を見直して下さい';

    /**
     * @var string
     */
    public readonly string $message;

    /**
     * constructor
     *
     * @param ToastStyle $style
     * @param string|null $message
     */
    public function __construct(
        public readonly ToastStyle $style = ToastStyle::Success,
        ?string $message = null,
    ) {
        // If no message is specified, set the default message based on the style
        $this->message = $message ?? $style->message();
    }

    /**
     * Create a toast session for input error
     *
     * @return self
     */
    public static function createInputError(): self
    {
        return new self(
            style: ToastStyle::Error,
            message: self::InputErrorMessage,
        );
    }

    /**
     * Create a new instance from the array
     *
     * @param array{
     *  toast-style: string,
     *  toast-message?: string,
     * } $data
     * @return array<string,mixed>
     */
    public static function fromArray(
        array $data
    ): self {
        return new self(
            style: ToastStyle::from(
                $data[ToastRenderer::ToastStyleKey],
            ),
            message: $data[ToastRenderer::ToastMessageKey] ?? null,
        );
    }
}
