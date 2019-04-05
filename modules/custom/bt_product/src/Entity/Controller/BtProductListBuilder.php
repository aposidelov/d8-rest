<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 22.11.2018
 * Time: 12:58
 */

namespace Drupal\bt_product\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a list controller for bt_product entity.
 *
 * @ingroup bt_product
 */
class BtProductListBuilder extends EntityListBuilder {

    /**
     * The url generator.
     *
     * @var \Drupal\Core\Routing\UrlGeneratorInterface
     */
    protected $urlGenerator;

    /**
     * {@inheritdoc}
     */
    public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
        return new static(
            $entity_type,
            $container->get('entity.manager')->getStorage($entity_type->id()),
            $container->get('url_generator')
        );
    }

    /**
     * Constructs a new BtProductListBuilder object.
     *
     * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
     *   The entity type definition.
     * @param \Drupal\Core\Entity\EntityStorageInterface $storage
     *   The entity storage class.
     * @param \Drupal\Core\Routing\UrlGeneratorInterface $url_generator
     *   The url generator.
     */
    public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, UrlGeneratorInterface $url_generator) {
        parent::__construct($entity_type, $storage);
        $this->urlGenerator = $url_generator;
    }

    /**
     * {@inheritdoc}
     *
     * We override ::render() so that we can add our own content above the table.
     * parent::render() is where EntityListBuilder creates the table using our
     * buildHeader() and buildRow() implementations.
     */
    public function render() {
        $build['description'] = [
            '#markup' => $this->t('BT product example implements a BT Product model. These bt products are fieldable entities. You can manage the fields on the <a href="@adminlink">BT product admin page</a>.', [
                '@adminlink' => $this->urlGenerator->generateFromRoute('bt_product.bt_product_settings'),
            ]),
        ];
        $build['table'] = parent::render();
        return $build;
    }

    /**
     * {@inheritdoc}
     *
     * Building the header and content lines for the contact list.
     *
     * Calling the parent::buildHeader() adds a column for the possible actions
     * and inserts the 'edit' and 'delete' links as defined for the entity type.
     */
    public function buildHeader() {
        $header['id'] = $this->t('BtProductID');
        $header['title'] = $this->t('Title');
        $header['sku'] = $this->t('SKU');
        //$header['price'] = $this->t('Price');
        return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity) {
        /* @var $entity \Drupal\bt_product\Entity\BtProduct */
        $row['id'] = $entity->id();
        $row['title'] = $entity->link();
        $row['sku'] = $entity->sku->value;
        //$row['price'] = $entity->price->value;
        return $row + parent::buildRow($entity);
    }
}