<?php

/**
 * @file
 * Contains \Drupal\my_card\Form\cardAddForm.
 */

namespace Drupal\bt_card\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\bt_card\Storage\Storage;
use Drupal\Core\Database\Database;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Link;

class AddForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormID() {
        return 'bt_card_add_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = array();

        $form['message'] = array(
            '#markup' => $this->t('Add an entry to the My card table (normal).'),
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
        );
        $form['add']['sku'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('SKU'),
            '#size' => 15,
        );

        $form['add']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Add'),
        );

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        // Confirm that price is numeric.
        if (!intval($form_state->getValue('price'))) {
            $form_state->setErrorByName('price', $this->t('Price needs to be a number'));
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
            'title' => $form_state->getValue('title'),
            'price' => $form_state->getValue('price'),
            'sku' => $form_state->getValue('sku'),
            'uid' => $account->id(),
        );
        $return = Storage::insert($entry);
        if ($return) {
            \Drupal::messenger()->addMessage($this->t('Created card @entry', array('@entry' => print_r($entry, TRUE))));
        }
    }
}
