<?php

/*
 * This file is part of the Sylius CMS Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\CmsPlugin\Validator;

use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Validator\Constraint\FileMimeTypeAllowed;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class FileMimeTypeAllowedValidator extends ConstraintValidator
{
    /** @param array<string, list<string>> $allowedMimeTypes */
    public function __construct(private readonly array $allowedMimeTypes = [])
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof FileMimeTypeAllowed) {
            throw new UnexpectedTypeException($constraint, FileMimeTypeAllowed::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof MediaInterface) {
            throw new UnexpectedValueException($value, MediaInterface::class);
        }

        if (!$value->hasFile()) {
            return;
        }

        $type = $value->getType();
        $allowedMimeTypes = $this->allowedMimeTypes[$type] ?? [];

        if ([] === $allowedMimeTypes) {
            return;
        }

        /** @var UploadedFile $file */
        $file = $value->getFile();
        $mimeType = $file->getMimeType();

        if (null !== $mimeType && $this->isAllowed($mimeType, $allowedMimeTypes)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ mime_type }}', $mimeType ?? '')
            ->setParameter('{{ allowed_mime_types }}', implode(', ', $allowedMimeTypes))
            ->atPath($constraint->field)
            ->addViolation()
        ;
    }

    /** @param list<string> $allowedMimeTypes */
    private function isAllowed(string $mimeType, array $allowedMimeTypes): bool
    {
        $mimeType = strtolower($mimeType);

        foreach ($allowedMimeTypes as $allowedMimeType) {
            $allowedMimeType = strtolower(trim($allowedMimeType));

            if ($mimeType === $allowedMimeType) {
                return true;
            }

            if (str_ends_with($allowedMimeType, '/*') &&
                str_starts_with($mimeType, substr($allowedMimeType, 0, -1))
            ) {
                return true;
            }
        }

        return false;
    }
}
