<?php

namespace Drupal\bt_custom;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Class BtCustomServiceProvider
 *
 * @package Drupal\bt_custom
 */
class BtCustomServiceProvider extends ServiceProviderBase {

    /**
     * {@inheritdoc}
     */
    public function alter(ContainerBuilder $container) {
        // Получаем обьявление сервиса.
        $definition = $container->getDefinition('bt_custom.shower');
        // Устанавливаем новое значение для 'class'.
        $definition->setClass('Drupal\bt_custom\Service\AlteredShower');
    }

}