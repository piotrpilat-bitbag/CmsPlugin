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

namespace Sylius\CmsPlugin\DependencyInjection;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\CmsPlugin\Controller\BlockController;
use Sylius\CmsPlugin\Controller\MediaController;
use Sylius\CmsPlugin\Controller\PageController;
use Sylius\CmsPlugin\Entity\Block;
use Sylius\CmsPlugin\Entity\BlockInterface;
use Sylius\CmsPlugin\Entity\Collection;
use Sylius\CmsPlugin\Entity\CollectionInterface;
use Sylius\CmsPlugin\Entity\ContentConfiguration;
use Sylius\CmsPlugin\Entity\ContentConfigurationInterface;
use Sylius\CmsPlugin\Entity\Media;
use Sylius\CmsPlugin\Entity\MediaInterface;
use Sylius\CmsPlugin\Entity\MediaTranslation;
use Sylius\CmsPlugin\Entity\MediaTranslationInterface;
use Sylius\CmsPlugin\Entity\Page;
use Sylius\CmsPlugin\Entity\PageInterface;
use Sylius\CmsPlugin\Entity\PageTranslation;
use Sylius\CmsPlugin\Entity\PageTranslationInterface;
use Sylius\CmsPlugin\Entity\Template;
use Sylius\CmsPlugin\Entity\TemplateInterface;
use Sylius\CmsPlugin\Form\Type\BlockType;
use Sylius\CmsPlugin\Form\Type\CollectionType;
use Sylius\CmsPlugin\Form\Type\ContentConfigurationType;
use Sylius\CmsPlugin\Form\Type\MediaType;
use Sylius\CmsPlugin\Form\Type\PageType;
use Sylius\CmsPlugin\Form\Type\TemplateType;
use Sylius\CmsPlugin\Repository\BlockRepository;
use Sylius\CmsPlugin\Repository\CollectionRepository;
use Sylius\CmsPlugin\Repository\ContentConfigurationRepository;
use Sylius\CmsPlugin\Repository\MediaRepository;
use Sylius\CmsPlugin\Repository\PageRepository;
use Sylius\CmsPlugin\Repository\TemplateRepository;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Resource\Factory\TranslatableFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sylius_cms');

        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->scalarNode('wysiwyg_editor')
            ->defaultValue('trix');

        $this->addResourcesSection($rootNode);
        $this->addTemplatesSection($rootNode);
        $this->addMediaSection($rootNode);

        return $treeBuilder;
    }

    private function addResourcesSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
            ->arrayNode('resources')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('block')
            ->addDefaultsIfNotSet()
            ->children()
            ->variableNode('options')->end()
            ->arrayNode('classes')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('model')->defaultValue(Block::class)->cannotBeEmpty()->end()
            ->scalarNode('interface')->defaultValue(BlockInterface::class)->cannotBeEmpty()->end()
            ->scalarNode('controller')->defaultValue(BlockController::class)->cannotBeEmpty()->end()
            ->scalarNode('factory')->defaultValue(Factory::class)->cannotBeEmpty()->end()
            ->scalarNode('repository')->defaultValue(BlockRepository::class)->cannotBeEmpty()->end()
            ->scalarNode('form')->defaultValue(BlockType::class)->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->arrayNode('collection')
            ->addDefaultsIfNotSet()
            ->children()
            ->variableNode('options')->end()
            ->arrayNode('classes')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('model')->defaultValue(Collection::class)->cannotBeEmpty()->end()
            ->scalarNode('interface')->defaultValue(CollectionInterface::class)->cannotBeEmpty()->end()
            ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
            ->scalarNode('factory')->defaultValue(Factory::class)->cannotBeEmpty()->end()
            ->scalarNode('repository')->defaultValue(CollectionRepository::class)->cannotBeEmpty()->end()
            ->scalarNode('form')->defaultValue(CollectionType::class)->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->arrayNode('content_configuration')
            ->addDefaultsIfNotSet()
            ->children()
            ->variableNode('options')->end()
            ->arrayNode('classes')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('model')->defaultValue(ContentConfiguration::class)->cannotBeEmpty()->end()
            ->scalarNode('interface')->defaultValue(ContentConfigurationInterface::class)->cannotBeEmpty()->end()
            ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
            ->scalarNode('factory')->defaultValue(Factory::class)->cannotBeEmpty()->end()
            ->scalarNode('repository')->defaultValue(ContentConfigurationRepository::class)->cannotBeEmpty()->end()
            ->scalarNode('form')->defaultValue(ContentConfigurationType::class)->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->arrayNode('media')
            ->addDefaultsIfNotSet()
            ->children()
            ->variableNode('options')->end()
            ->arrayNode('classes')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('model')->defaultValue(Media::class)->cannotBeEmpty()->end()
            ->scalarNode('interface')->defaultValue(MediaInterface::class)->cannotBeEmpty()->end()
            ->scalarNode('controller')->defaultValue(MediaController::class)->cannotBeEmpty()->end()
            ->scalarNode('factory')->defaultValue(TranslatableFactory::class)->cannotBeEmpty()->end()
            ->scalarNode('repository')->defaultValue(MediaRepository::class)->cannotBeEmpty()->end()
            ->scalarNode('form')->defaultValue(MediaType::class)->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->arrayNode('translation')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('classes')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('model')->defaultValue(MediaTranslation::class)->cannotBeEmpty()->end()
            ->scalarNode('interface')->defaultValue(MediaTranslationInterface::class)->cannotBeEmpty()->end()
            ->scalarNode('factory')->defaultValue(Factory::class)->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->arrayNode('page')
            ->addDefaultsIfNotSet()
            ->children()
            ->variableNode('options')->end()
            ->arrayNode('classes')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('model')->defaultValue(Page::class)->cannotBeEmpty()->end()
            ->scalarNode('interface')->defaultValue(PageInterface::class)->cannotBeEmpty()->end()
            ->scalarNode('controller')->defaultValue(PageController::class)->cannotBeEmpty()->end()
            ->scalarNode('factory')->defaultValue(TranslatableFactory::class)->cannotBeEmpty()->end()
            ->scalarNode('repository')->defaultValue(PageRepository::class)->cannotBeEmpty()->end()
            ->scalarNode('form')->defaultValue(PageType::class)->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->arrayNode('translation')
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('classes')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('model')->defaultValue(PageTranslation::class)->cannotBeEmpty()->end()
            ->scalarNode('interface')->defaultValue(PageTranslationInterface::class)->cannotBeEmpty()->end()
            ->scalarNode('factory')->defaultValue(Factory::class)->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->arrayNode('template')
            ->addDefaultsIfNotSet()
            ->children()
            ->variableNode('options')->end()
            ->arrayNode('classes')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('model')->defaultValue(Template::class)->cannotBeEmpty()->end()
            ->scalarNode('interface')->defaultValue(TemplateInterface::class)->cannotBeEmpty()->end()
            ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
            ->scalarNode('factory')->defaultValue(Factory::class)->cannotBeEmpty()->end()
            ->scalarNode('repository')->defaultValue(TemplateRepository::class)->cannotBeEmpty()->end()
            ->scalarNode('form')->defaultValue(TemplateType::class)->cannotBeEmpty()->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end()
            ->end();
    }

    private function addMediaSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('media')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('allowed_mime_types')
                            ->info('Optional whitelist of MIME types accepted during media upload, per media provider type (image, video, file, ...). An empty or missing list means no restriction. Wildcards such as "image/*" are supported.')
                            ->useAttributeAsKey('type')
                            ->arrayPrototype()
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(static fn (string $value): array => [$value])
                                ->end()
                                ->scalarPrototype()->cannotBeEmpty()->end()
                            ->end()
                            ->defaultValue([
                                'image' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/tiff', 'image/bmp', 'image/vnd.adobe.photoshop'],
                                'video' => ['video/mp4', 'video/webm', 'video/quicktime', 'video/x-msvideo'],
                                'file' => ['image/*', 'video/*', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/x-zip-compressed', 'application/zip', 'application/x-rar-compressed', 'application/gzip', 'application/illustrator', 'text/plain', 'text/csv'],
                            ])
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addTemplatesSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('pages')
                            ->scalarPrototype()->end()
                        ->end()
                        ->arrayNode('blocks')
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
