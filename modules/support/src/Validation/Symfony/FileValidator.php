<?php

namespace CmsTool\Support\Validation\Symfony;

use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\FileValidator as SymfonyFileValidator;

class FileValidator extends SymfonyFileValidator
{
    /**
     * {@inheritDoc}
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (
            $constraint instanceof File
            && $value instanceof UploadedFileInterface
        ) {
            if (in_array($value->getError(), $constraint->ignoreErrors)) {
                return;
            }

            $value = (new Psr7ToSymfonyUploadedFileTransformer())($value);
        }

        parent::validate($value, $constraint);
    }
}
