<?php

/**
 * @file
 * Contains \Drupal\my_product\Form\ProductEditForm.
 */

namespace Drupal\bt_card\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\bt_card\Storage\Storage;

class EditForm extends FormBase {
	protected $id;
	/**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'bt_card_update_form';
  }

  /**
   * Sample UI to update a record.
   */
  public function buildForm(array $form,
                            FormStateInterface $form_state,
                            $id = '') {
  	$this->id = $id;

  	$card = Storage::load(array('id' => $id));  	
    // Wrap the form in a div.    

  	$form['id'] = array(
  		'#type' => 'value',
  		'#value' => $this->id,
		);
    $form['title'] = array(
      '#type' => 'textfield',
      '#title' => t('Title'),
      '#size' => 15,
      '#default_value' => $card[0]->title,
    );
    $form['price'] = array(
      '#type' => 'textfield',
      '#title' => t('Price'),
      '#size' => 15,
      '#default_value' => $card[0]->price,
    );
    $form['sku'] = array(
      '#type' => 'textfield',
      '#title' => t('SKU'),
      '#size' => 15,
      '#default_value' => $card[0]->sku,
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
  public function validateForm(array &$form,
                               FormStateInterface $form_state) {
    // Confirm that age is numeric.
    if (empty($form_state->getValue('title'))) {
      $form_state->setErrorByName('title', t('Title is required'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form,
                             FormStateInterface $form_state) {
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
    $count = Storage::update($entry);
      \Drupal::messenger()->addMessage(
    	$this->t('Updated product @entry (@count row updated)', array(
	      '@count' => $count,
	      '@entry' => print_r($entry, TRUE),
      )
    ));
  }
}
