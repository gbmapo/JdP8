<?php

namespace Drupal\sel\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Exchange entity.
 *
 * @ingroup sel
 *
 * @ContentEntityType(
 *   id = "exchange",
 *   label = @Translation("Exchange"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\sel\ExchangeListBuilder",
 *     "views_data" = "Drupal\sel\Entity\ExchangeViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\sel\Form\ExchangeForm",
 *       "add" = "Drupal\sel\Form\ExchangeForm",
 *       "edit" = "Drupal\sel\Form\ExchangeForm",
 *       "delete" = "Drupal\sel\Form\ExchangeDeleteForm",
 *     },
 *     "access" = "Drupal\sel\ExchangeAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\sel\ExchangeHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "exchange",
 *   admin_permission = "administer exchange entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "exchange",
 *   },
 *   links = {
 *     "canonical" = "/sel/exchange/{exchange}",
 *     "add-form" = "/sel/exchange/add",
 *     "edit-form" = "/sel/exchange/{exchange}/edit",
 *     "delete-form" = "/sel/exchange/{exchange}/delete",
 *     "collection" = "/sel/exchange",
 *   },
 *   field_ui_base_route = "exchange.settings"
 * )
 */
class Exchange extends ContentEntityBase implements ExchangeInterface
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
  public function getExchange()
  {
    return $this->get('exchange')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setExchange($exchange)
  {
    $this->set('exchange', $exchange);
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
    $fields['date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Exchange Date'))
      ->setSetting('datetime_type', 'date')
      ->setDefaultValue(array(0 => array(
        'default_date_type' => 'now',
        'default_date' => 'now',
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
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['from_seliste_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Giver'))
      ->setDescription(t('SÉListe who receives seeds'))
      ->setSetting('target_type', 'person')
      ->setSetting('handler', 'views')
      ->setSetting('handler_settings', [
        'view' => [
          'view_name' => 'sel_selistes',
          'display_name' => 'entity_reference_1',
        ],
      ])
      ->setDisplayOptions('view', array(
        'weight' => $weight,
      ))
      ->setDisplayOptions('form', array(
        'weight' => $weight,
        'type' => 'options_select',
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['to_seliste_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Beneficiary'))
      ->setDescription(t('SÉListe who gives seeds'))
      ->setSetting('target_type', 'person')
      ->setSetting('handler', 'views')
      ->setSetting('handler_settings', [
        'view' => [
          'view_name' => 'sel_selistes',
          'display_name' => 'entity_reference_1',
        ],
      ])
      ->setDisplayOptions('view', array(
        'weight' => $weight,
      ))
      ->setDisplayOptions('form', array(
        'weight' => $weight,
        'type' => 'options_select',
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['exchange'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Exchange'))
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
        'size' => '128',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['value'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Number of seeds'))
      ->setSetting('unsigned', TRUE)
      ->setDefaultValue(0)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
// ----------------------------------------------------------------------------
    $fields['owner_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Creator'))
      ->setSetting('target_type', 'person')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\sel\Entity\Service::getCurrentUserId')
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
