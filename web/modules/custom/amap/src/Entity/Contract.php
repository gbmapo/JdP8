<?php

namespace Drupal\amap\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Contract entity.
 *
 * @ingroup amap
 *
 * @ContentEntityType(
 *   id = "contract",
 *   label = @Translation("Contract"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\amap\ContractListBuilder",
 *     "views_data" = "Drupal\amap\Entity\ContractViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\amap\Form\ContractForm",
 *       "add" = "Drupal\amap\Form\ContractForm",
 *       "edit" = "Drupal\amap\Form\ContractForm",
 *       "delete" = "Drupal\amap\Form\ContractDeleteForm",
 *     },
 *     "access" = "Drupal\amap\ContractAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\amap\ContractHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "contract",
 *   admin_permission = "administer contract entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *   },
 *   links = {
 *     "canonical" = "/amap/contract/{contract}",
 *     "add-form" = "/amap/contract/add",
 *     "edit-form" = "/amap/contract/{contract}/edit",
 *     "delete-form" = "/amap/contract/{contract}/delete",
 *     "collection" = "/amap/contract",
 *   },
 *   field_ui_base_route = "contract.settings"
 * )
 */
class Contract extends ContentEntityBase implements ContractInterface
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
    $fields['type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Type of contract'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'contract_type')
      ->setDisplayOptions('view', array(
        'weight' => $weight,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => $weight,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setSettings([
        'max_length' => 255,
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
    $fields['startdate'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Start date of contract'))
      ->setSetting('datetime_type', 'date')
      ->setDefaultValue(array(0 => array(
        'default_date_type' => 'now',
        'default_date' => 'now',
      )))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'weight' => $weight,
      ))
      ->setDisplayOptions('form', array(
        'weight' => $weight,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $fields['enddate'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('End date of contract'))
      ->setSetting('datetime_type', 'date')
      ->setDefaultValue(array(0 => array(
        'default_date_type' => 'now',
        'default_date' => 'now',
      )))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'weight' => $weight,
      ))
      ->setDisplayOptions('form', array(
        'weight' => $weight,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['referent_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Referent of contract'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'person')
      ->setDisplayOptions('view', array(
        'weight' => $weight,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => $weight,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['isvisible'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Visible'))
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('view', array(
        'settings' => [
          'format' => 'yes-no',
        ],
        'weight' => $weight,
      ))
      ->setDisplayOptions('form', array(
        'settings' => [
          'display_label' => TRUE,
        ],
        'type' => 'boolean_checkbox',
        'weight' => $weight,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['file1'] = BaseFieldDefinition::create('file')
      ->setLabel(t('Source file'))
      ->setDescription(t('Source file (may be modified)'))
      ->setSettings([
        'target_type' => 'file',
        'file_extensions' => 'doc docx odt pages',
        'uri_scheme' => 'private',
        'file_directory' => 'contracts',
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
      ->setReadOnly(TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['file2'] = BaseFieldDefinition::create('file')
      ->setLabel(t('pdf file'))
      ->setDescription(t('To be downloaded by AMAPiens'))
      ->setSettings([
        'target_type' => 'file',
        'file_extensions' => 'pdf',
        'uri_scheme' => 'private',
        'file_directory' => 'contracts',
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
      ->setReadOnly(TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['isopenforsubscription'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Open for subscription'))
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('view', array(
        'settings' => [
          'format' => 'yes-no',
        ],
        'weight' => $weight,
      ))
      ->setDisplayOptions('form', array(
        'settings' => [
          'display_label' => TRUE,
        ],
        'type' => 'boolean_checkbox',
        'weight' => $weight,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $fields['owner_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\association\Entity\Service::getCurrentUserId')
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
