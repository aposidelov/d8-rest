<?php
/**
 * @file
 * Contains \Drupal\my_product\Controller\ProductController.
 */

namespace Drupal\my_product\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Component\Utility\SafeMarkup;

class ProductController extends ControllerBase {
  
  public function productsList() {
  	$page = array();

  	// We are going to output the results in a table with a nice header.
    $header = array(
      // The header gives the table the information it needs in order to make
      // the query calls for ordering. TableSort uses the field information
      // to know what database column to sort by.
      array('data' => $this->t('Title'), 'field' => 'p.title'),
      array('data' => $this->t('Price'), 'field' => 'p.price'),
      array('data' => $this->t('SKU'), 'field' => 'p.sku'),      
      array('data' => $this->t('Actions')),
    );

    // Using the TableSort Extender is what tells  the query object that we
    // are sorting.
    $query = db_select('my_product', 'p')
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

    $rows = array();
    foreach ($result as $row) {   

      $edit_url = Url::fromRoute('my_product.edit_form', array('id' => $row->id));      
			$edit_url->setOptions($link_options);      
      $edit_link = \Drupal::l($this->t('edit'), $edit_url);
      
      $delete_url = Url::fromRoute('my_product.delete_form', array('id' => $row->id));      
			$delete_url->setOptions($link_options);      
      $delete_link = \Drupal::l($this->t('delete'), $delete_url);
      $links = SafeMarkup::format('@edit_link @delete_link', ['@edit_link' => $edit_link, '@delete_link' => $delete_link]);
      
      $rows[] = array('data' => array(
      	$row->title,
      	$row->price,
      	$row->sku,      	
      	$links,
    	));
    }

    $add_url = Url::fromRoute('my_product.add_form');
    $add_url->setOptions($link_options);
    $add_link = \Drupal::l($this->t('Add new product'), $add_url);
    
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
}

?>