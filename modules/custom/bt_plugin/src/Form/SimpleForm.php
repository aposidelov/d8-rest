<?php

/**
 * @file
 * Contains \Drupal\bt_plugin\Form\SimpleForm.
 */

namespace Drupal\bt_plugin\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;

class SimpleForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormID() {
        return 'bt_plugin_simple_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = array();

        $form['message'] = array(
            '#markup' => $this->t('Simple form description.'),
        );

        $form['add'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t('Add a card'),
        );
        $form['add']['title'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Title'),
            '#size' => 15,
            '#required' => TRUE,

        );
        $form['add']['price'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Price'),
            '#size' => 15,
            '#required' => TRUE,
        );        
        $form['actions'] = array('#type' => 'actions', '#tree' => FALSE);
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary',
        );

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        // Confirm that price is numeric.
        /*if (!intval($form_state->getValue('price'))) {
            $form_state->setErrorByName('price', $this->t('Price needs to be a number'));
        }*/
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Gather the current user so the new record has ownership.
        $account = \Drupal::currentUser(); 
        $result = array();
        $result['title'] = $form_state->getValue('title');
        $result['price'] = $form_state->getValue('price');
        $result['sku'] = $form_state->getValue('sku');
        $result['uid'] = $account->id();
        \Drupal::messenger()->addMessage($this->t('Created card @result', array('@result' => print_r($result, TRUE))));        
    }
}
