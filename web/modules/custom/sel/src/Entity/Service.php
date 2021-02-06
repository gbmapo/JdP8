<?php

namespace Drupal\sel\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;
use Drupal\link\LinkItemInterface;

/**
 * Defines the Service entity.
 *
 * @ingroup sel
 *
 * @ContentEntityType(
 *   id = "service",
 *   label = @Translation("Service"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\sel\ServiceListBuilder",
 *     "views_data" = "Drupal\sel\Entity\ServiceViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\sel\Form\ServiceForm",
 *       "add" = "Drupal\sel\Form\ServiceForm",
 *       "edit" = "Drupal\sel\Form\ServiceForm",
 *       "delete" = "Drupal\sel\Form\ServiceDeleteForm",
 *     },
 *     "access" = "Drupal\sel\ServiceAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\sel\ServiceHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "service",
 *   admin_permission = "administer service entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "service",
 *   },
 *   links = {
 *     "canonical" = "/sel/service/{service}",
 *     "add-form" = "/sel/service/add",
 *     "edit-form" = "/sel/service/{service}/edit",
 *     "delete-form" = "/sel/service/{service}/delete",
 *     "collection" = "/sel/service",
 *   },
 *   field_ui_base_route = "service.settings"
 * )
 */
class Service extends ContentEntityBase implements ServiceInterface
{

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values)
  {
    parent::preCreate($storage_controller, $values);
    $values += [
      'owner_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getService()
  {
    return $this->get('service')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setService($service)
  {
    $this->set('service', $service);
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

  /**
   * {@inheritdoc}
   */
  public function getOwner()
  {
    return $this->get('owner_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId()
  {
    return $this->get('owner_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid)
  {
    $this->set('owner_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account)
  {
    $this->set('owner_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
  {
    $fields = parent::baseFieldDefinitions($entity_type);
// ----------------------------------------------------------------------------
    $weight = 0;
// ----------------------------------------------------------------------------
    $weight++;
    $fields['action'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Action'))
      ->setRequired(TRUE)
      ->setSettings(array(
        'allowed_values' => array(
          'O' => t('Offer'),
          'D' => t('Demand'),
        ),
      ))
      ->setDisplayOptions('view', array(
        'weight' => $weight,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => $weight,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['category'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Category'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'service_category')
      ->setDisplayOptions('view', array(
        'link' => '0',
        'weight' => $weight,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => $weight,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['service'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Service'))
      ->setRequired(TRUE)
      ->setSettings([
        'max_length' => 128,
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
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['comment'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Comment'))
      ->setSettings([
        'max_length' => 1024,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'type' => 'string_long',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textarea',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['duedate'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Due Date'))
      ->setRequired(TRUE)
      ->setSetting('datetime_type', 'date')
      ->setDefaultValue(array(0 => array(
        'default_date_type' => 'now',
        'default_date' => '+3 months',
      )))
      ->setDisplayOptions('view', [
        'type' => 'datetime_custom',
        'settings' => [
          'date_format' => 'd/m/y',
        ],
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', array(
        'weight' => $weight,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['isurgent'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Urgent'))
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('view', array(
        'settings' => [
          'format' => 'yes-no',
        ],
        'weight' => $weight,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'boolean_checkbox',
        'weight' => $weight,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Published'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('view', array(
        'settings' => [
          'format' => 'yes-no',
        ],
        'weight' => $weight,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'boolean_checkbox',
        'weight' => $weight,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['picture'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Picture'))
      ->setSettings([
        'file_extensions' => 'gif jpeg jpg png',
        'file_directory' => 'sel',
        'alt_field_required' => 0,
      ])
      ->setCardinality(3)
      ->setDisplayOptions('view', [
        'type' => 'default',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type' => 'image_image',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['file'] = BaseFieldDefinition::create('file')
      ->setLabel(t('File'))
      ->setSettings([
        'file_extensions' => 'pdf',
        'file_directory' => 'sel',
        'description_field' => TRUE,
      ])
      ->setDisplayOptions('view', [
        'type' => 'file',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type' => 'file',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['link'] = BaseFieldDefinition::create('link')
      ->setLabel(t('Link'))
      ->setDisplayOptions('view', [
        'type' => 'link',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type' => 'link_default',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $fields['owner_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('SÉListe'))
      ->setSetting('target_type', 'person')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\sel\Entity\Service::getCurrentUserId')
      ->setReadOnly(TRUE);
// ----------------------------------------------------------------------------
    $fields['additiondate'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Addition Date'))
      ->setSetting('datetime_type', 'date')
      ->setDefaultValue(array(0 => array(
        'default_date_type' => 'now',
        'default_date' => 'now',
      )))
      ->setReadOnly(TRUE);
// ----------------------------------------------------------------------------
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'));
// ----------------------------------------------------------------------------
    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'));
// ----------------------------------------------------------------------------
    return $fields;
  }

  public static function getCurrentUserId()
  {
    return [\Drupal::currentUser()->id()];
  }

}
