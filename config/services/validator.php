<?php

/*
 * This file is part of the Sylius CMS Plugin package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\CmsPlugin\Validator\FileMimeTypeAllowedValidator;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->defaults()
        ->public();

    $services->set('sylius_cms.validator.file_mime_type_allowed', FileMimeTypeAllowedValidator::class)
        ->args(['%sylius_cms.media.allowed_mime_types%'])
        ->tag('validator.constraint_validator', ['alias' => FileMimeTypeAllowedValidator::class]);
};
