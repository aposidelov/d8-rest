<?php
/**
 * @file
 * Contains \Drupal\my_shop\Controller\CartController.
 */

namespace Drupal\my_shop\Controller;

use Drupal\Core\Controller\ControllerBase;

class CartController extends ControllerBase {
  public function cart() {
    return array(
        '#type' => 'markup',
        '#markup' => $this->t('Hello, World!'),
    );
  }
}

?>