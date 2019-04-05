<?php

/**
 * @file
 * Contains \Drupal\my_product\Form\ProductDeleteForm.
 */

namespace Drupal\my_product\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\my_product\Storage\ProductStorage;
use Drupal\Core\Url;

class ProductDeleteForm extends ConfirmFormBase {
	
	protected $id;

	public function getFormId() {
    return 'my_product_delete_form';
  }

  public function getQuestion() {
    return $this->t('Delete');  // Actually, a heading.
  }

  public function getDescription() {
    return $this->t('Are you sure ...');
  }

  public function getConfirmText() {
    return $this->t('Delete');
  }  

  public function getCancelUrl() {
    return new Url('my_product.list');
  }

  public function buildForm(array $form, 
  													FormStateInterface $form_state, 
                            $id = '') {
    $this->id = $id;

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, 
  													FormStateInterface $form_state) {

    // Use $this->oid to update the database, etc. ...
    \Drupal::logger('ttt')->notice('<pre>'.print_r($this->id, TRUE).'</pre>');
    db_delete('my_product')
      ->condition( 'id', $this->id )
      ->execute();
    //watchdog('$form_state', '<pre>'.print_r($form_state, TRUE).'</pre>');
    // Return to project listing page
    $form_state->setRedirectUrl(new Url('my_product.list', ['route' => 'parameters']));    
  }
}

?>