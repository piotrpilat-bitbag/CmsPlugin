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

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container) {
    $env = $_ENV['APP_ENV'] ?? 'dev';

    if (str_starts_with($env, 'test')) {
        // Sylius <2.3 ships these Behat service definitions as XML; 2.3+ converted them to PHP.
        $behatServicesFile = file_exists(__DIR__ . '/../../../vendor/sylius/sylius/src/Sylius/Behat/Resources/config/services.php')
            ? '../../../vendor/sylius/sylius/src/Sylius/Behat/Resources/config/services.php'
            : '../../../vendor/sylius/sylius/src/Sylius/Behat/Resources/config/services.xml';

        $container->import($behatServicesFile);
        $container->import('@SyliusCmsPlugin/tests/Behat/Resources/services.php');
    }
};
