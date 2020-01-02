<?php

namespace Drupal\sel\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Service category entity.
 *
 * @ingroup sel
 *
 * @ContentEntityType(
 *   id = "service_category",
 *   label = @Translation("Service category"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\sel\ServiceCategoryListBuilder",
 *     "views_data" = "Drupal\sel\Entity\ServiceCategoryViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\sel\Form\ServiceCategoryForm",
 *       "add" = "Drupal\sel\Form\ServiceCategoryForm",
 *       "edit" = "Drupal\sel\Form\ServiceCategoryForm",
 *       "delete" = "Drupal\sel\Form\ServiceCategoryDeleteForm",
 *     },
 *     "access" = "Drupal\sel\ServiceCategoryAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\sel\ServiceCategoryHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "service_category",
 *   admin_permission = "administer service category entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *   },
 *   links = {
 *     "canonical" = "/sel/service_category/{service_category}",
 *     "add-form" = "/sel/service_category/add",
 *     "edit-form" = "/sel/service_category/{service_category}/edit",
 *     "delete-form" = "/sel/service_category/{service_category}/delete",
 *     "collection" = "/sel/service_category",
 *   },
 *   field_ui_base_route = "service_category.settings"
 * )
 */
class ServiceCategory extends ContentEntityBase implements ServiceCategoryInterface
{

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values)
  {
    parent::preCreate($storage_controller, $values);
  }

  /**
   * {@inheritdoc}
   */
  public function getName()
  {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name)
  {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime()
  {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp)
  {
    $this->set('created', $timestamp);
    return $this;
  }


  public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
  {
    $fields = parent::baseFieldDefinitions($entity_type);
// ----------------------------------------------------------------------------
    $weight = 0;
// ----------------------------------------------------------------------------
    $weight++;
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setSettings([
        'max_length' => 64,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
// ----------------------------------------------------------------------------
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'));
// ----------------------------------------------------------------------------
    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'));
// ----------------------------------------------------------------------------
    return $fields;
  }

}
