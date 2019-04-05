<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 22.11.2018
 * Time: 13:20
 */

namespace Drupal\bt_product\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\bt_product\BtProductInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Entity\EntityChangedTrait;

/**
 * Class BtProduct
 * @package Drupal\bt_product\Entity
 *
 * @ContentEntityType(
 *   id = "bt_product",
 *   label = @Translation("Bt Product"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bt_product\Entity\Controller\BtProductListBuilder",
 *     "form" = {
 *       "default" = "Drupal\bt_product\Form\BtProductForm",
 *       "delete" = "Drupal\bt_product\Form\BtProductDeleteForm",
 *     },
 *     "access" = "Drupal\bt_product\BtProductAccessControlHandler",
 *   },
 *   list_cache_contexts = { "user" },
 *   base_table = "bt_product",
 *   admin_permission = "administer bt product entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/bt_product/{bt_product}",
 *     "edit-form" = "/bt_product/{bt_product}/edit",
 *     "delete-form" = "/bt_product/{bt_product}/delete",
 *     "collection" = "/bt_product/list"
 *   },
 *   field_ui_base_route = "bt_product.bt_product_settings",
 * )
 *
 */
class BtProduct extends ContentEntityBase implements BtProductInterface {

    use EntityChangedTrait;

    /**
     * {@inheritdoc}
     *
     * When a new entity instance is added, set the user_id entity reference to
     * the current user as the creator of the instance.
     */
    public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
        parent::preCreate($storage_controller, $values);
        $values += [
            'user_id' => \Drupal::currentUser()->id(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner() {
        return $this->get('user_id')->entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwnerId() {
        return $this->get('user_id')->target_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwnerId($uid) {
        $this->set('user_id', $uid);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner(UserInterface $account) {
        $this->set('user_id', $account->id());
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * Define the field properties here.
     *
     * Field name, type and size determine the table structure.
     *
     * In addition, we can define how the field and its content can be manipulated
     * in the GUI. The behaviour of the widgets used can be determined here.
     */
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

        // Standard field, used as unique if primary index.
        $fields['id'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('ID'))
            ->setDescription(t('The ID of the Bt Product entity.'))
            ->setReadOnly(TRUE);

        // Standard field, unique outside of the scope of the current project.
        $fields['uuid'] = BaseFieldDefinition::create('uuid')
            ->setLabel(t('UUID'))
            ->setDescription(t('The UUID of the Bt Product entity.'))
            ->setReadOnly(TRUE);

        // Name field for the contact.
        // We set display options for the view as well as the form.
        // Users with correct privileges can change the view and edit configuration.
        $fields['title'] = BaseFieldDefinition::create('string')
            ->setLabel(t('Title'))
            ->setDescription(t('The title of the Bt Product entity.'))
            ->setSettings([
                'max_length' => 255,
                'text_processing' => 0,
            ])
            // Set no default value.
            ->setDefaultValue(NULL)
            ->setDisplayOptions('view', [
                'label' => 'above',
                'type' => 'string',
                'weight' => -6,
            ])
            ->setDisplayOptions('form', [
                'type' => 'string_textfield',
                'weight' => -6,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);

        $fields['sku'] = BaseFieldDefinition::create('string')
            ->setLabel(t('SKU'))
            ->setDescription(t('The sku identifier of the Bt Product entity.'))
            ->setSettings([
                'max_length' => 255,
                'text_processing' => 0,
            ])
            // Set no default value.
            ->setDefaultValue(NULL)
            ->setDisplayOptions('view', [
                'label' => 'above',
                'type' => 'string',
                'weight' => -5,
            ])
            ->setDisplayOptions('form', [
                'type' => 'string_textfield',
                'weight' => -5,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);
        $fields['field_bt_product_image'] = BaseFieldDefinition::create('image')
            ->setLabel(t('Image'))
            ->setDescription(t('The image of the Bt Product entity.'))
            ->setSettings([
                'file_directory' => '[date:custom:Y]-[date:custom:m]',
                'file_extensions' => 'png gif jpg jpeg',
                'max_filesize' => '5MB',
                'max_resolution' => '',
                'min_resolution' => '',
                'alt_field' => 0,
                'alt_field_required' => 1,
                'title_field' => 0,
                'title_field_required' => 0,
                'default_image' => [
                    'uuid' => '',
                    'alt' => '',
                    'title' => '',
                    'width' => '',
                    'height' => '',
                ],
                'handler' => 'default:file',
                'handler_settings' => [],
                'uri_scheme' => 'public',
                'target_type' => 'file',
                'display_field' => '',
                'display_default' => '',
            ])
            // Set no default value.
            ->setDefaultValue(NULL)
            ->setDisplayOptions('view', [
                'weight' => 9,
                'label' => 'inline',
                'settings' => [
                    'image_style' => 'large',
                    'image_link' => 'content',
                ],
                'third_party_settings' => [],
                'type' => 'image',
                'region' => 'content',
            ])
            ->setDisplayOptions('form', [
                'weight' => 125,
                'settings' => [
                    'preview_image_style' => 'medium',
                    'progress_indicator' => 'throbber',
                ],
                'third_party_settings' => [],
                'type' => 'image_image',
                'region' => 'content',
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE)
            ->setCardinality(3);
        // Owner field of the contact.
        // Entity reference field, holds the reference to the user object.
        // The view shows the user name field of the user.
        // The form presents a auto complete field for the user name.
        $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
            ->setLabel(t('User Name'))
            ->setDescription(t('The Name of the associated user.'))
            ->setSetting('target_type', 'user')
            ->setSetting('handler', 'default')
            ->setDisplayOptions('view', [
                'label' => 'above',
                'type' => 'author',
                'weight' => -3,
            ])
            ->setDisplayOptions('form', [
                'type' => 'entity_reference_autocomplete',
                'settings' => [
                    'match_operator' => 'CONTAINS',
                    'size' => 60,
                    'placeholder' => '',
                ],
                'weight' => -3,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);
        $fields['langcode'] = BaseFieldDefinition::create('language')
            ->setLabel(t('Language code'))
            ->setDescription(t('The language code of ContentEntityExample entity.'));
        $fields['created'] = BaseFieldDefinition::create('created')
            ->setLabel(t('Created'))
            ->setDescription(t('The time that the entity was created.'));

        $fields['changed'] = BaseFieldDefinition::create('changed')
            ->setLabel(t('Changed'))
            ->setDescription(t('The time that the entity was last edited.'));
        /*$fields['price'] = BaseFieldDefinition::create('decimal')
            ->setLabel(t('Price'))
            ->setDescription(t('The price of the Bt Product entity.'))
            ->setSettings([
                'max_length' => 255,
                'text_processing' => 0,
            ])
            // Set no default value.
            ->setDefaultValue(NULL)
            ->setDisplayOptions('view', [
                'label' => 'above',
                'type' => 'string',
                'weight' => -5,
            ])
            ->setDisplayOptions('form', [
                'type' => 'string_textfield',
                'weight' => -5,
            ])
            ->setDisplayConfigurable('form', TRUE)
            ->setDisplayConfigurable('view', TRUE);
        */
        return $fields;
    }
}