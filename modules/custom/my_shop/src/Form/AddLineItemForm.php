<?php

namespace Drupal\my_shop\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class RemoveProductForm extends FormBase {
    /**
     * Returns a unique string identifying the form.
     * @return string
     *   The unique string identifying the form.
     */public function getFormId() {
    // TODO: Implement getFormId() method.
}

    /**
     * Form constructor.
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     * @return array
     *   The form structure.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        // TODO: Implement buildForm() method.
    }

    /**
     * Form submission handler.
     * @param array $form
     *   An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The current state of the form.
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // TODO: Implement submitForm() method.
    }
}


?>