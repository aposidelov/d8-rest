<?php
/**
 * @file
 * Contains \Drupal\bt_custom\Controller\cardController.
 */

namespace Drupal\bt_custom\Controller;

use Drupal\Core\Controller\ControllerBase;

class Controller extends ControllerBase {
  public function simplePage() {
    $page = array();
    $page['#theme'] = 'bt_custom_simple_page';
    $page['test_data'] = array(
      '#markup' => 'Test data',
    );
    return $page;
  }
}