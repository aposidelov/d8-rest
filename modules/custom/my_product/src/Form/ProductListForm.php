<?php
/**
 * @file
 * Contains \Drupal\my_product\Form\ProductListForm.
 */

namespace Drupal\my_product\Form;

use Drupal\Core\Url;
use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\my_product\Storage\ProductStorage;

class ProductListForm extends FormBase {
  
  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'my_product_list_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
  	$form['#theme'] = 'my_product_form';
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
    $query = db_select('my_product', 'p')
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

    $form['products'] = array(
      '#type' => 'table',
      '#header' => $header,      
      '#attributes' => array(
        'id' => 'products',
      ),
      '#tabledrag' => array(
        array(
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'product-weight',    
        ),
      ),
    );

    $add_url = Url::fromRoute('my_product.add_form');
    $add_url->setOptions($link_options);
    $add_link = \Drupal::l($this->t('Add new product'), $add_url);

    $form['add_link'] = array(
  		'#markup' => $add_link,
		);

    $rows = array();
    foreach ($result as $row) {   
      $key = $row->id;
      $form['products'][$key]['#attributes']['class'][] = 'draggable';      
    	$form['products'][$key]['title'] = array(
    		'#markup' => $row->title,
  		);
    	$form['products'][$key]['price'] = array(
    		'#markup' => $row->price,
  		);
  		$form['products'][$key]['sku'] = array(
    		'#markup' => $row->sku,
  		);
			$form['products'][$key]['weight'] = array(
        '#type' => 'weight',
        '#delta' => 10,
        '#title' => $this->t('Weight'),
        '#title_display' => 'invisible',
        '#default_value' => $row->weight,
        '#attributes' => array(
          'class' => array('product-weight'),
        ),        
      );            

      $edit_url = Url::fromRoute('my_product.edit_form', array('id' => $row->id));      
			$edit_url->setOptions($link_options);      
      $edit_link = \Drupal::l($this->t('edit'), $edit_url);
      
      $delete_url = Url::fromRoute('my_product.delete_form', array('id' => $row->id));      
			$delete_url->setOptions($link_options);      
      $delete_link = $link = \Drupal::l($this->t('delete'), $delete_url);
      $links = SafeMarkup::format('@edit_link @delete_link', ['@edit_link' => $edit_link, '@delete_link' => $delete_link]);

      $form['products'][$key]['operations'] = array(
    		'#markup' => $links,
  		);

      $form['products'][$key]['#attributes']['class'][] = 'draggable';      
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
    $ordered_products = $form_state->getValue('products');
    foreach ($ordered_products as $product_id => $data) {
      $product = array(
        'id' => $product_id,
        'weight' => $data['weight'],
      );      
      ProductStorage::update($product);
    }    
    drupal_set_message($this->t('Products have been rearranged'));
  }  
}

?>