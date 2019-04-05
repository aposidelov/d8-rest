<?php
/**
 * @file
 * Contains \Drupal\bt_card\Form\ProductListForm.
 */

namespace Drupal\bt_card\Form;

use Drupal\Core\Url;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\bt_card\Storage\Storage;
use Drupal\Core\Database\Database;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Link;

class ListForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormID() {
        return 'bt_card_list_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form,
                              FormStateInterface $form_state) {
        $form['#theme'] = 'bt_card_list_form';
        // We are going to output the results in a table with a nice header.
        $header = array(
            // The header gives the table the information it needs in order to make
            // the query calls for ordering. TableSort uses the field information
            // to know what database column to sort by.
            array('data' => $this->t('Title'), 'field' => 'p.title'),
            array('data' => $this->t('Price'), 'field' => 'p.price'),
            array('data' => $this->t('SKU'), 'field' => 'p.sku'),
            array('data' => $this->t('Weight')),
            array('data' => $this->t('Actions')),
        );
        // Using the TableSort Extender is what tells  the query object that we
        // are sorting.
        $query = Database::getConnection()->select('bt_card', 'p')
            ->orderBy('weight', 'ASC')
            ->extend('Drupal\Core\Database\Query\TableSortExtender');
        $query->fields('p');

        // Don't forget to tell the query object how to find the header information.
        $result = $query
            ->orderByHeader($header)
            ->execute();

        $destination = \Drupal::destination()->getAsArray();
        $link_options = array(
            'query' => $destination,
        );

        $form['cards'] = array(
            '#type' => 'table',
            '#header' => $header,
            '#attributes' => array(
                'id' => 'cards',
            ),
            '#tabledrag' => array(
                array(
                    'action' => 'order',
                    'relationship' => 'sibling',
                    'group' => 'product-weight',
                ),
            ),
        );

        $add_url = Url::fromRoute('bt_card.add_form');
        $add_url->setOptions($link_options);
        $add_link = Link::fromTextAndUrl($this->t('Add new card'), $add_url)->toString();

        $form['add_link'] = array(
            '#markup' => $add_link,
        );

        $form['use_ajax_container']['use_ajax'] = [
            '#type' => 'link',
            '#title' => $this->t('Add Card (ajax)'),
            '#url' => Url::fromRoute('bt_card.modal_add_form', ['nojs' => 'ajax']),
            '#attributes' => [
                'class' => ['use-ajax'],
                'data-dialog-type' => 'modal',
                'data-dialog-options' => json_encode(['width' => '50%']),
                // Add this id so that we can test this form.
                'id' => 'bt-modal-link',
            ],
        ];

        $rows = array();
        foreach ($result as $row) {
            $key = $row->id;
            $form['cards'][$key]['#attributes']['class'][] = 'draggable';
            $form['cards'][$key]['title'] = array(
                '#markup' => $row->title,
            );
            $form['cards'][$key]['price'] = array(
                '#markup' => $row->price,
            );
            $form['cards'][$key]['sku'] = array(
                '#markup' => $row->sku,
            );
            $form['cards'][$key]['weight'] = array(
                '#type' => 'weight',
                '#delta' => 10,
                '#title' => $this->t('Weight'),
                '#title_display' => 'invisible',
                '#default_value' => $row->weight,
                '#attributes' => array(
                    'class' => array('product-weight'),
                ),
            );

            $edit_url = Url::fromRoute('bt_card.edit_form', array('id' => $row->id));
            $edit_url->setOptions($link_options);
            $edit_link = Link::fromTextAndUrl($this->t('edit'), $edit_url)->toString();

            $view_link = Link::fromTextAndUrl($this->t('view'),
                new Url('bt_card.card_view',
                    ['id' => $row->id]
                )
            )->toString();

            $delete_url = Url::fromRoute('bt_card.delete_form', array('id' => $row->id));
            $delete_url->setOptions($link_options);
            $delete_link = $link = Link::fromTextAndUrl($this->t('delete'), $delete_url)->toString();
            $links = new FormattableMarkup('@edit_link @delete_link @view_link', ['@edit_link' => $edit_link, '@delete_link' => $delete_link, '@view_link' => $view_link]);

            $form['cards'][$key]['operations'] = array(
                '#markup' => $links,
            );

            $form['cards'][$key]['#attributes']['class'][] = 'draggable';
        }

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
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $ordered_cards = $form_state->getValue('cards');
        foreach ($ordered_cards as $card_id => $data) {
            $card = array(
                'id' => $card_id,
                'weight' => $data['weight'],
            );
            Storage::update($card);
        }
        //\Drupal::messenger()
        $this->messenger()->addMessage($this->t('Cards have been rearranged 2'));
    }
}
