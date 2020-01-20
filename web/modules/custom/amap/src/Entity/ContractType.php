<?php

namespace Drupal\amap\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Contract type entity.
 *
 * @ingroup amap
 *
 * @ContentEntityType(
 *   id = "contract_type",
 *   label = @Translation("Contract type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\amap\ContractTypeListBuilder",
 *     "views_data" = "Drupal\amap\Entity\ContractTypeViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\amap\Form\ContractTypeForm",
 *       "add" = "Drupal\amap\Form\ContractTypeForm",
 *       "edit" = "Drupal\amap\Form\ContractTypeForm",
 *       "delete" = "Drupal\amap\Form\ContractTypeDeleteForm",
 *     },
 *     "access" = "Drupal\amap\ContractTypeAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\amap\ContractTypeHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "contract_type",
 *   admin_permission = "administer contract type entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *   },
 *   links = {
 *     "canonical" = "/amap/contract_type/{contract_type}",
 *     "add-form" = "/amap/contract_type/add",
 *     "edit-form" = "/amap/contract_type/{contract_type}/edit",
 *     "delete-form" = "/amap/contract_type/{contract_type}/delete",
 *     "collection" = "/amap/contract_type",
 *   },
 *   field_ui_base_route = "contract_type.settings"
 * )
 */
class ContractType extends ContentEntityBase implements ContractTypeInterface
{

  use EntityChangedTrait;

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
        'max_length' => 50,
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
    $weight++;
    $fields['displayedname'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Displayed Name'))
      ->setSettings([
        'max_length' => 50,
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
    $weight++;
    $fields['numberofquantities'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Number of quantities'))
      ->setDefaultValue(1)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'integer',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type' => 'integer',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['formheader'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Header for Form'))
      ->setSettings([
        'max_length' => 1024,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
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
    $fields['exportheader'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Header for Form'))
      ->setSettings([
        'max_length' => 1024,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
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
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'));
// ----------------------------------------------------------------------------
    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'));
// ----------------------------------------------------------------------------
    return $fields;
  }

}
