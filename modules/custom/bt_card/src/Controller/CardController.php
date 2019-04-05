<?php
/**
 * @file
 * Contains \Drupal\bt_card\Controller\cardController.
 */

namespace Drupal\bt_card\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\bt_card\Storage\Storage;
use Drupal\Core\Database\Database;
use Drupal\Core\Link;
use Drupal\Component\Render\FormattableMarkup;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AppendCommand;

class CardController extends ControllerBase {

    public function cardList() {
        $page = array();

        // We are going to output the results in a table with a nice header.
        $header = array(
            // The header gives the table the information it needs in order to make
            // the query calls for ordering. TableSort uses the field information
            // to know what database column to sort by.
            array('data' => $this->t('Title'), 'field' => 'c.title'),
            array('data' => $this->t('Price'), 'field' => 'c.price'),
            array('data' => $this->t('SKU'), 'field' => 'c.sku'),
            array('data' => $this->t('Actions')),
        );
        // Using the TableSort Extender is what tells  the query object that we
        // are sorting.
        $query = Database::getConnection()->select('bt_card', 'c')
            ->extend('Drupal\Core\Database\Query\TableSortExtender');
        $query->fields('c');

        // Don't forget to tell the query object how to find the header information.
        $result = $query
            ->orderByHeader($header)
            ->execute();

        $destination = \Drupal::destination()->getAsArray();
        $link_options = array(
            'query' => $destination,
        );

        $rows = array();
        foreach ($result as $row) {

            $edit_url = Url::fromRoute('bt_card.edit_form', array('id' => $row->id));
            $edit_url->setOptions($link_options);
            $edit_link = Link::fromTextAndUrl($this->t('edit'), $edit_url)->toString();

            $delete_url = Url::fromRoute('bt_card.delete_form', array('id' => $row->id));
            $delete_url->setOptions($link_options);
            $delete_link = Link::fromTextAndUrl($this->t('delete'), $delete_url)->toString();
            $view_link = Link::fromTextAndUrl($this->t('view'),
                new Url('bt_card.card_view',
                    ['id' => $row->id]
                )
            )->toString();
            $links = new FormattableMarkup('@edit_link @delete_link @view_link', ['@edit_link' => $edit_link, '@delete_link' => $delete_link, '@view_link' => $view_link]);

            $rows[] = array('data' => array(
                $row->title,
                $row->price,
                $row->sku,
                $links,
            ));
        }

        $add_url = Url::fromRoute('bt_card.add_form');
        $add_url->setOptions($link_options);
        $add_link = Link::fromTextAndUrl($this->t('Add new card'), $add_url)->toString();

        $page['add_link'] = array(
            '#markup' => $add_link,
        );

        $page['table'] = array(
            '#theme' => 'table',
            '#header' => $header,
            '#rows' => $rows,
            '#tabledrag' => array(
                array(
                    'action' => 'order',
                    'relationship' => 'sibling',
                    'group' => 'example-item-weight',
                ),
            ),
        );

        return $page;
    }

    public function cardView($id) {
        $page = array();
        $page['#theme'] = 'bt_card_view';
        $page['ajax_link'] = [
            '#type' => 'details',
            '#title' => $this->t('This is the AJAX link'),
            '#open' => TRUE,
        ];
        $page['ajax_link']['link'] = [
            '#type' => 'link',
            '#title' => $this->t('Click me'),
            // We have to ensure that Drupal's Ajax system is loaded.
            '#attached' => ['library' => ['core/drupal.ajax']],
            // We add the 'use-ajax' class so that Drupal's AJAX system can spring
            // into action.
            '#attributes' => ['class' => ['use-ajax']],
            // The URL for this link element is the callback. In our case, it's route
            // ajax_example.ajax_link_callback, which maps to ajaxLinkCallback()
            // below. The route has a /{nojs} section, which is how the callback can
            // know whether the request was made by AJAX or some other means where
            // JavaScript won't be able to handle the result. If the {nojs} part of
            // the path is replaced with 'ajax', then the request was made by AJAX.
            '#url' => Url::fromRoute('bt_card.ajax_link_callback',
                ['id' => $id, 'nojs' => 'ajax']),
        ];
        $page['ajax_link']['destination'] = [
            '#type' => 'container',
            '#attributes' => ['id' => ['bt-card-destination-div']],
        ];
        //$page['card'] = $card[0];
        $card = Storage::load(array('id' => $id));
        $page['card'] = array(
            '#value' => $card[0]
        );
        return $page;
    }

    public function title($id) {
        $card = Storage::load(array('id' => $id));
        return $card[0]->title;
    }

    public function ajaxLinkCallback($id, $nojs = 'ajax') {
        // Determine whether the request is coming from AJAX or not.
        if ($nojs == 'ajax') {
            $output = $this->t("This is some content delivered via AJAX, id:$id");
            $response = new AjaxResponse();
            $response->addCommand(new AppendCommand('#bt-card-destination-div', $output));

            // See ajax_example_advanced.inc for more details on the available
            // commands and how to use them.
            // $page = array('#type' => 'ajax', '#commands' => $commands);
            // ajax_deliver($response);
            return $response;
        }
        $response = new Response($this->t("This is some content delivered via a page load,id:$id"));
        return $response;
    }
}
