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

namespace Sylius\CmsPlugin\Validator\Constraint;

use Sylius\CmsPlugin\Validator\FileMimeTypeAllowedValidator;
use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

final class FileMimeTypeAllowed extends Constraint
{
    public string $message = 'The MIME type of the uploaded file ({{ mime_type }}) is not allowed. Allowed MIME types: {{ allowed_mime_types }}.';

    public string $field;

    #[HasNamedArguments]
    public function __construct(
        string $field,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(null, $groups, $payload);

        $this->field = $field;
        $this->message = $message ?? $this->message;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return FileMimeTypeAllowedValidator::class;
    }
}
