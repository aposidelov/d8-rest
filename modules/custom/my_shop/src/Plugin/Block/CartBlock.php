<?php
namespace Drupal\my_shop\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
/**
 * Provides a 'Cart' Block
 *
 * @Block(
 *   id = "cart_block",
 *   admin_label = @Translation("Cart"),
 * )
 */
class CartBlock extends BlockBase implements BlockPluginInterface {
  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $default_config = \Drupal::config('cart_block.settings');
    return array(
      'empty_text' => $default_config->get('mini_commerce_blocks.cart_block.empty_text')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
  	$config = $this->getConfiguration();

    if (!empty($config['empty_text'])) {
      $empty_text = $config['empty_text'];
    }
    else {
      $empty_text = $this->t('Cart is empty!');
    }
    return array(
      '#markup' => $empty_text,
    );
  }
  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['cart_block_empty_text'] = array (
      '#type' => 'textfield',
      '#title' => $this->t('Empty text'),
      '#description' => $this->t('Empty text of the block'),
      '#default_value' => isset($config['empty_text']) ? $config['empty_text'] : ''
    );

    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('empty_text', $form_state->getValue('cart_block_empty_text'));
  }
}

?>