<?php

namespace Drupal\bt_card\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingsForm extends ConfigFormBase {

    /**
     * Gets the configuration names that will be editable.
     * @return array
     *   An array of configuration object names that are editable if called in
     *   conjunction with the trait's config() method.
     */
    protected function getEditableConfigNames() {
        return ['bt_card.settings'];
    }

    /**
     * Returns a unique string identifying the form.
     * @return string
     *   The unique string identifying the form.
     */
    public function getFormId() {
        return 'bt_card_settings_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('bt_card.settings');
        $form['cards_per_page'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Cards per page from code'),
            '#default_value' => $config->get('cards_per_page'),
        ];

        $form['help_message'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Help message from code'),
            '#default_value' => $config->get('help_message'),
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        parent::validateForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $this->config('bt_card.settings')
            ->set('cards_per_page', $form_state->getValue('cards_per_page'))
            ->set('help_message', $form_state->getValue('help_message'))
            ->save();
        parent::submitForm($form, $form_state);
    }
}