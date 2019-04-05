<?php

/**
 * @file
 * Contains \Drupal\my_card\Form\CardAddForm.
 */

namespace Drupal\bt_card\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\bt_card\Storage\Storage;
use Drupal\Core\Database\Database;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\bt_card\Form\AddForm;

class ModalAddForm extends FormBase {
    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container) {
        // Create a new form object and inject its services.
        $form = new static();
        $form->setRequestStack($container->get('request_stack'));
        $form->setStringTranslation($container->get('string_translation'));
        $form->setMessenger($container->get('messenger'));
        return $form;
    }
    /**
     * {@inheritdoc}
     */
    public function getFormID() {
        return 'bt_card_modal_add_form';
    }
    /**
     * Helper method so we can have consistent dialog options.
     *
     * @return string[]
     *   An array of jQuery UI elements to pass on to our dialog form.
     */
    protected static function getDataDialogOptions() {
        return [
            'width' => '50%',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $nojs = NULL) {
        // Add the core AJAX library.
        $add_form = new AddForm();
        $form = $add_form->buildForm($form, $form_state);
        $form['#attached']['library'][] = 'core/drupal.ajax';

        if ($nojs == 'ajax') {
            $form['status_messages'] = [
                '#type' => 'status_messages',
                '#weight' => -999,
            ];
            $form['add']['submit']['#ajax'] = [
                'callback' => '::ajaxSubmitForm',
                'event' => 'click',
            ];
        }

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
            'sku' 	=> $form_state->getValue('sku'),
            'uid' 	=> $account->id(),
        );
        $return = Storage::insert($entry);
        if ($return) {
            \Drupal::messenger()->addMessage($this->t('Created card @entry', array('@entry' => print_r($entry, TRUE))));
        }
    }

    /**
     * Implements the submit handler for the modal dialog AJAX call.
     *
     * @param array $form
     *   Render array representing from.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   Current form state.
     *
     * @return \Drupal\Core\Ajax\AjaxResponse
     *   Array of AJAX commands to execute on submit of the modal form.
     */
    public function ajaxSubmitForm(array &$form, FormStateInterface $form_state) {
        // We begin building a new ajax reponse.
        $response = new AjaxResponse();

        // If the user submitted the form and there are errors, show them the
        // input dialog again with error messages. Since the title element is
        // required, the empty string wont't validate and there will be an error.
        if ($form_state->getErrors()) {
            // If there are errors, we can show the form again with the errors in
            // the status_messages section.
            $form['status_messages'] = [
                '#type' => 'status_messages',
                '#weight' => -10,
            ];
            $response->addCommand(new OpenModalDialogCommand($this->t('Errors'), $form, static::getDataDialogOptions()));
        }
        // If there are no errors, show the output dialog.
        else {
            // We don't want any messages that were added by submitForm().
            $this->messenger()->deleteAll();
            // We use FormattableMarkup to handle sanitizing the input.
            // @todo: There's probably a better way to do this.
            $response->addCommand(new CloseModalDialogCommand());
            $currentURL = Url::fromRoute('bt_card.list_drag');
            $response->addCommand(new RedirectCommand($currentURL->toString()));

            /*
            $title = new FormattableMarkup(':title', [':title' => $form_state->getValue('title')]);
            // This will be the contents for the modal dialog.
            $content = [
                '#type' => 'item',
                '#markup' => $this->t("Your specified title of '%title' appears in this modal dialog.", ['%title' => $title]),
            ];
            */
            // Add the OpenModalDialogCommand to the response. This will cause Drupal
            // AJAX to show the modal dialog. The user can click the little X to close
            // the dialog.
            //$response->addCommand(new OpenModalDialogCommand($title, $content, static::getDataDialogOptions()));
        }

        // Finally return our response.
        return $response;
    }

}