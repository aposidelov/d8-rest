<?php

/**
 * @file
 * Contains \Drupal\my_product\Form\ProductEditForm.
 */

namespace Drupal\my_product\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\my_product\Storage\ProductStorage;

class ProductEditForm extends FormBase {
	protected $id;
	/**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'my_product_update_form';
  }

  /**
   * Sample UI to update a record.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = '') {
  	$this->id = $id;

  	$product = ProductStorage::load(array('id' => $id));  	
    // Wrap the form in a div.    

  	$form['id'] = array(
  		'#type' => 'value',
  		'#value' => $this->id,
		);
    $form['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#size' => 15,
      '#default_value' => $product[0]->title,
    );
    $form['price'] = array(
      '#type' => 'textfield',
      '#title' => t('Price'),
      '#size' => 15,
      '#default_value' => $product[0]->price,
    );
    $form['sku'] = array(
      '#type' => 'textfield',
      '#title' => t('SKU'),
      '#size' => 15,
      '#default_value' => $product[0]->sku,
      '#description' => t('Values greater than 127 will cause an exception'),
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Update'),
    );

    return $form;
  }  

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Confirm that age is numeric.
    if (!intval($form_state->getValue('price'))) {
      $form_state->setErrorByName('price', t('Price needs to be a number'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Gather the current user so the new record has ownership.
    $account = \Drupal::currentUser();
    // Save the submitted entry.
    $entry = array(
      'id'		=> $form_state->getValue('id'),
      'title'	=> $form_state->getValue('title'),
      'price'	=> $form_state->getValue('price'),
      'sku'		=> $form_state->getValue('sku'),
      'uid'		=> $account->id(),
    );
    $count = ProductStorage::update($entry);
    drupal_set_message(
    	t('Updated product @entry (@count row updated)', array(
	      '@count' => $count,
	      '@entry' => print_r($entry, TRUE),
      )
    ));
  }
}

?>