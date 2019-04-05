<?php

/**
 * @file
 * Contains \Drupal\bt_custom\Form\SimpleForm.
 */

namespace Drupal\bt_custom\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;

class SimpleForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormID() {
        return 'bt_custom_simple_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = array();

        $form['message'] = array(
            '#markup' => $this->t('Simple form description.'),
        );

        $form['field'] = array(
            '#type' => 'fieldset',
            '#title' => $this->t('Field info'),
        );
        $form['field']['entity_type'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Entity type'),
            '#size' => 15,
            '#default_value' => 'node',
            '#required' => TRUE,

        );
        $form['field']['bundle'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Bundle'),
            '#size' => 15,
            '#default_value' => 'article',
            '#required' => TRUE,
        );
        $form['field']['field_name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Field name'),
            '#size' => 15,
            '#default_value' => 'field_',
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
        $entity_type = $form_state->getValue('entity_type');
        $bundle = $form_state->getValue('bundle');
        $field_name = $form_state->getValue('field_name');
        //$config = \Drupal\field\Entity\FieldConfig::loadByName($entity_type, $bundle, $field_name);
        //$config =  \Drupal\field\Entity\FieldStorageConfig::loadByName($entity_type, $field_name);

        $bundle_fields = \Drupal::getContainer()->get('entity_field.manager')->getFieldDefinitions($entity_type, $bundle);
        $field_definition = $bundle_fields[$field_name];

        //$widget = \Drupal::service('plugin.manager.field.widget')->getInstance(['field_definition' => $field_definition]);
        //\Drupal::logger('fd-form')->notice('<pre>'.print_r($widget, TRUE).'</pre>');
        //\Drupal::messenger()->addMessage($this->t('Created card @entity_type, @bundle, @field_name', array('@entity_type' => $entity_type, '@bundle' => $bundle, '@field_name' => $field_name)));
        //\Drupal::logger('fd')->notice('<pre>'.print_r($field_definition->getSettings(), TRUE).'</pre>');

        $view_entity = entity_get_display($entity_type, $bundle, 'default');
        $form_entity = entity_get_form_display($entity_type, $bundle, 'default');

        $view_display = $view_entity->getComponent($field_name);
        $form_display = $form_entity->getComponent($field_name);

        \Drupal::logger('$view_display')->notice('<pre>'.print_r($view_display, TRUE).'</pre>');
        \Drupal::logger('$form_display')->notice('<pre>'.print_r($form_display, TRUE).'</pre>');
        \Drupal::logger('getSettings')->notice('<pre>'.print_r($field_definition->getSettings(), TRUE).'</pre>');

        //$bundle_fields = \Drupal::getContainer()->get('entity_field.manager')->getFieldDefinitions($entity_type);
        //$definition = $bundle_fields[$field_name];
        //\Drupal::logger('fd')->notice('<pre>'.print_r($bundle_fields, TRUE).'</pre>');

        //$foo = \Drupal\field\Entity\FieldConfig::loadByName($entity_type, $bundle, $field_name);

        /*$view_modes = \Drupal::service('entity_display.repository')
            ->getViewModeOptionsByBundle(
                $entity_type, $bundle
            );
        $settings = \Drupal::service('entity_type.manager')
            ->getStorage('entity_view_display')
            ->load($entity_type . '.' . $bundle . '.default')
            ->getRenderer($field_name)
            ->getFieldDefinition();
        \Drupal::logger('fd-view')->notice('<pre>'.print_r($settings, TRUE).'</pre>');
        */
        /*foreach (array_keys($view_modes) as $view_mode) {
            $settings = \Drupal::service('entity_type.manager')
                ->getStorage('entity_view_display')
                ->load($entity_type . '.' . $bundle . '.' . $view_mode)
                ->getRenderer($field_name)
                ->getSettings();
            \Drupal::logger($entity_type . '.' . $bundle . '.' . $view_mode)->notice('<pre>'.print_r($settings, TRUE).'</pre>');
            // Get settings I need.
        }*/

        //\Drupal::logger('fd-view')->notice('<pre>'.print_r($field_definition, TRUE).'</pre>');
        //\Drupal::logger('fd-form')->notice('<pre>'.print_r($field_definition->getDisplayOptions('form'), TRUE).'</pre>');
    }
}
