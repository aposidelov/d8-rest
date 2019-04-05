<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 26.11.2015
 * Time: 18:02
 */

namespace Drupal\my_shop\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class MyShopSettingsForm extends ConfigFormBase {

    /**
     * Gets the configuration names that will be editable.
     * @return array
     *   An array of configuration object names that are editable if called in
     *   conjunction with the trait's config() method.
     */
    protected function getEditableConfigNames() {
        return ['my_shop.settings'];
    }

    /**
     * Returns a unique string identifying the form.
     * @return string
     *   The unique string identifying the form.
     */
    public function getFormId() {
        return 'my_shop_settings_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('my_shop.settings');

        $form['shop_title'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('Shop title'),
          '#default_value' => $config->get('shop_title'),
        );
        $currencies = array(
            'USD' => '$',
            'EUR' => $this->t('Euro'),
            'PZL' => $this->t('Zloty')
        );
        $form['shop_currency'] = array(
            '#type' => 'select',
            '#title' => $this->t('Currency'),
            '#default_value' => $config->get('shop_currency'),
            '#options' => $currencies,
            '#description' => $this->t('The number of replies a topic must have to be considered "hot".'),
        );
        return parent::buildForm($form, $form_state); // TODO: Change the autogenerated stub
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $this->config('my_shop.settings')
            ->set('shop.title', $form_state->getValue('shop_title'))
            ->set('shop.currency', $form_state->getValue('shop_currency'))
            ->save();
        parent::submitForm($form, $form_state); // TODO: Change the autogenerated stub
    }
}