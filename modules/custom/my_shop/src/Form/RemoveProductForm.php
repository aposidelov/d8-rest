<?php

/**
 * @file
 * Contains \Drupal\my_shop\Form\RemoveProductForm.
 */

namespace Drupal\my_shop\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class RemoveProductForm extends FormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'my_shop_remove_product_form';
  }

  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {    
    // Default settings
    $config = $this->config('my_shop.settings');

    // Page title field
    $form['product_id'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Product Id:'),
      '#default_value' => '',
      '#description' => $this->t('Enter a product id you want to remove'),
    );  

    $form['save'] = array(
    	'#type' => 'submit',
    	'#value' => $this->t('Save'),  
    );
    $form['delete'] = array(
    	'#type' => 'submit',
    	'#value' => $this->t('Delete'),  
    );      


    return $form;
	}	

	/**
   * {@inheritdoc}.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {            
    $clicked_button = &$form_state->getTriggeringElement()['#parents'][0];        
    $product_id = $form_state->getValue('product_id');

    if ($clicked_button == 'save') {
    	drupal_set_message($this->t('Save product Id: !product_id', array('!product_id' => $product_id)));
    } elseif ($clicked_button == 'delete') {
    	drupal_set_message($this->t('Delete product Id: !product_id', array('!product_id' => $product_id)));
    } 
    
    
    //$config->set('loremipsum.source_text', $form_state->getValue('source_text'));
    //$config->set('loremipsum.page_title', $form_state->getValue('page_title'));
    //$config->save();
    //return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}.
   */
  protected function getEditableConfigNames() {
    return [
      'my_shop.settings',
    ];
  }
}

?>