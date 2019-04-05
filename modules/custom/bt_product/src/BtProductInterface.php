<?php

namespace Drupal\bt_product;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Contact entity.
 *
 * We have this interface so we can join the other interfaces it extends.
 *
 * @ingroup bt_product
 */
interface BtProductInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
