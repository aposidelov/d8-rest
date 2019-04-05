<?php

/**
 * @file
 * Contains \Drupal\bt_card\Form\ProductDeleteForm.
 */

namespace Drupal\bt_card\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\bt_card\Storage\Storage;
use Drupal\Core\Database\Database;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;

class ConfirmDeleteForm extends ConfirmFormBase {
	
	protected $id;

	public function getFormId() {
    return 'bt_card_delete_form';
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
    return new Url('bt_card.list');
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
    Storage::delete(array('id' => $this->id));
    /*db_delete('bt_card')
      ->condition( 'id', $this->id )
      ->execute();
    */
    //watchdog('$form_state', '<pre>'.print_r($form_state, TRUE).'</pre>');
    // Return to project listing page
    $form_state->setRedirectUrl(new Url('bt_card.list', ['route' => 'parameters']));    
  }
}
