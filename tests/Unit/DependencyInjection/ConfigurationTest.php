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

namespace Tests\Sylius\CmsPlugin\Unit\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Sylius\CmsPlugin\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testAllowedMimeTypesAreEmptyByDefault(): void
    {
        self::assertSame([], $this->processMediaConfiguration([]));
    }

    public function testAllowedMimeTypesCanBeConfiguredPerMediaType(): void
    {
        $processed = $this->processMediaConfiguration(['media' => ['allowed_mime_types' => [
            'image' => ['image/jpeg', 'image/png'],
            'file' => 'application/pdf',
        ]]]);

        self::assertSame([
            'image' => ['image/jpeg', 'image/png'],
            'file' => ['application/pdf'],
        ], $processed);
    }

    public function testAllowedMimeTypesCannotContainEmptyValues(): void
    {
        $this->assertConfigurationIsInvalid(
            [['media' => ['allowed_mime_types' => ['image' => ['']]]]],
            'cannot contain an empty value',
        );
    }

    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }

    /**
     * @param array<string, mixed> $configuration
     *
     * @return array<string, list<string>>
     */
    private function processMediaConfiguration(array $configuration): array
    {
        $processed = (new Processor())->processConfiguration($this->getConfiguration(), [$configuration]);

        return $processed['media']['allowed_mime_types'];
    }
}
