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

namespace Sylius\CmsPlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\CmsPlugin\Form\Type\Translation\MediaTranslationType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class MediaType extends AbstractResourceType
{
    /**
     * @param array<string, string> $providers
     * @param array<string, list<string>> $allowedMimeTypes
     */
    public function __construct(
        string $dataClass,
        array $validationGroups = [],
        private array $providers = [],
        private array $allowedMimeTypes = [],
    ) {
        parent::__construct($dataClass, $validationGroups);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $builder->getData();

        $builder
            ->add('code', TextType::class, [
                'label' => 'sylius_cms.ui.code',
                'disabled' => null !== $data && null !== $data->getCode(),
            ])
            ->add('name', TextType::class, [
                'label' => 'sylius_cms.ui.name',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'sylius_cms.ui.type',
                'choices' => $this->providers,
            ])
            ->add('file', FileType::class, [
                'label' => 'sylius_cms.ui.file',
                'attr' => $this->getFileAttributes(),
            ])
            ->add('collections', CollectionAutocompleteType::class, [
                'label' => 'sylius_cms.ui.collections',
                'required' => false,
                'multiple' => true,
                'by_reference' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius_cms.ui.enabled',
            ])
            ->add('saveWithOriginalName', CheckboxType::class, [
                'required' => false,
                'label' => 'sylius_cms.ui.save_with_original_name',
            ])
            ->add('channels', ChannelChoiceType::class, [
                'label' => 'sylius_cms.ui.channels',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('path', TextType::class, [
                'label' => 'sylius_cms.ui.path',
                'disabled' => true,
                'required' => false,
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => MediaTranslationType::class,
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_cms_media';
    }

    /**
     * The accept attribute is only a browser-side hint; the authoritative check is done by the
     * FileMimeTypeAllowed constraint, which narrows the list down to the selected media type.
     *
     * @return array<string, string>
     */
    private function getFileAttributes(): array
    {
        $mimeTypes = [];

        foreach ($this->allowedMimeTypes as $mimeTypesPerType) {
            foreach ($mimeTypesPerType as $mimeType) {
                $mimeTypes[trim($mimeType)] = true;
            }
        }

        if ([] === $mimeTypes) {
            return [];
        }

        return ['accept' => implode(',', array_keys($mimeTypes))];
    }
}
