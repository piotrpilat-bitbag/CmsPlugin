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

namespace Tests\Sylius\CmsPlugin\Unit\Validator;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Validator\Constraint\FileMimeTypeAllowed;
use Sylius\CmsPlugin\Validator\FileMimeTypeAllowedValidator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

final class FileMimeTypeAllowedValidatorTest extends TestCase
{
    private ExecutionContextInterface&MockObject $context;

    protected function setUp(): void
    {
        $this->context = $this->createMock(ExecutionContextInterface::class);
    }

    public function testNoViolationWhenNoMimeTypesAreConfiguredForType(): void
    {
        $validator = $this->createValidator(['video' => ['video/mp4']]);

        $this->context->expects(self::never())->method('buildViolation');

        $validator->validate($this->createMedia('image', 'image/svg+xml'), $this->createConstraint());
    }

    public function testNoViolationWhenNothingIsConfiguredAtAll(): void
    {
        $validator = $this->createValidator([]);

        $this->context->expects(self::never())->method('buildViolation');

        $validator->validate($this->createMedia('image', 'image/svg+xml'), $this->createConstraint());
    }

    public function testNoViolationWhenMimeTypeIsOnTheWhitelist(): void
    {
        $validator = $this->createValidator(['image' => ['image/jpeg', 'image/png']]);

        $this->context->expects(self::never())->method('buildViolation');

        $validator->validate($this->createMedia('image', 'image/png'), $this->createConstraint());
    }

    public function testNoViolationWhenMimeTypeMatchesAWildcard(): void
    {
        $validator = $this->createValidator(['file' => ['application/*']]);

        $this->context->expects(self::never())->method('buildViolation');

        $validator->validate($this->createMedia('file', 'application/pdf'), $this->createConstraint());
    }

    public function testNoViolationWhenNoFileIsUploaded(): void
    {
        $validator = $this->createValidator(['image' => ['image/jpeg']]);

        $media = $this->createMock(MediaInterface::class);
        $media->method('hasFile')->willReturn(false);

        $this->context->expects(self::never())->method('buildViolation');

        $validator->validate($media, $this->createConstraint());
    }

    public function testViolationWhenMimeTypeIsNotOnTheWhitelist(): void
    {
        $constraint = $this->createConstraint();
        $validator = $this->createValidator(['image' => ['image/jpeg', 'image/png']]);

        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violationBuilder->method('setParameter')->willReturnSelf();
        $violationBuilder->expects(self::once())->method('atPath')->with('file')->willReturnSelf();
        $violationBuilder->expects(self::once())->method('addViolation');

        $this->context->expects(self::once())
            ->method('buildViolation')
            ->with($constraint->message)
            ->willReturn($violationBuilder)
        ;

        $validator->validate($this->createMedia('image', 'image/svg+xml'), $constraint);
    }

    public function testViolationWhenMimeTypeCannotBeDetermined(): void
    {
        $constraint = $this->createConstraint();
        $validator = $this->createValidator(['image' => ['image/jpeg']]);

        $violationBuilder = $this->createMock(ConstraintViolationBuilderInterface::class);
        $violationBuilder->method('setParameter')->willReturnSelf();
        $violationBuilder->method('atPath')->willReturnSelf();
        $violationBuilder->expects(self::once())->method('addViolation');

        $this->context->method('buildViolation')->willReturn($violationBuilder);

        $validator->validate($this->createMedia('image', null), $constraint);
    }

    /** @param array<string, list<string>> $allowedMimeTypes */
    private function createValidator(array $allowedMimeTypes): FileMimeTypeAllowedValidator
    {
        $validator = new FileMimeTypeAllowedValidator($allowedMimeTypes);
        $validator->initialize($this->context);

        return $validator;
    }

    private function createConstraint(): FileMimeTypeAllowed
    {
        return new FileMimeTypeAllowed(field: 'file');
    }

    private function createMedia(string $type, ?string $mimeType): MediaInterface&MockObject
    {
        $file = $this->createMock(UploadedFile::class);
        $file->method('getMimeType')->willReturn($mimeType);

        $media = $this->createMock(MediaInterface::class);
        $media->method('hasFile')->willReturn(true);
        $media->method('getFile')->willReturn($file);
        $media->method('getType')->willReturn($type);

        return $media;
    }
}
