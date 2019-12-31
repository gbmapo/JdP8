<?php

namespace Drupal\amap\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Contract subscription entity.
 *
 * @ingroup amap
 *
 * @ContentEntityType(
 *   id = "contract_subscription",
 *   label = @Translation("Contract subscription"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\amap\ContractSubscriptionListBuilder",
 *     "views_data" = "Drupal\amap\Entity\ContractSubscriptionViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\amap\Form\ContractSubscriptionForm",
 *       "add" = "Drupal\amap\Form\ContractSubscriptionForm",
 *       "edit" = "Drupal\amap\Form\ContractSubscriptionForm",
 *       "delete" = "Drupal\amap\Form\ContractSubscriptionDeleteForm",
 *     },
 *     "access" = "Drupal\amap\ContractSubscriptionAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\amap\ContractSubscriptionHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "contract_subscription",
 *   admin_permission = "administer contract subscription entities",
 *   entity_keys = {
 *     "id" = "id",
 *   },
 *   links = {
 *     "canonical" = "/amap/contract_subscription/{contract_subscription}",
 *     "add-form" = "/amap/contract_subscription/add",
 *     "edit-form" = "/amap/contract_subscription/{contract_subscription}/edit",
 *     "delete-form" = "/amap/contract_subscription/{contract_subscription}/delete",
 *     "collection" = "/amap/contract_subscription",
 *   },
 *   field_ui_base_route = "contract_subscription.settings"
 * )
 */
class ContractSubscription extends ContentEntityBase implements ContractSubscriptionInterface
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
    $fields['contract_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Contract'))
      ->setSetting('target_type', 'contract')
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
    $fields['member_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Member'))
      ->setSetting('target_type', 'member')
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
    $fields['sharedwith_member_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Shared with'))
      ->setSetting('target_type', 'member')
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
    $fields['comment'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Comment'))
      ->setSettings(array(
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string_long',
        'weight' => $weight,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textarea',
        'weight' => $weight,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['file'] = BaseFieldDefinition::create('file')
      ->setLabel(t('pdf file'))
      ->setSettings([
        'target_type' => 'file',
        'file_extensions' => 'pdf',
        'uri_scheme' => 'private',
        'file_directory' => 'contracts/subscriptions',
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
    $fields['quantity01'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 01'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity02'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 02'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity03'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 03'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity04'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 04'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity05'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 05'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity06'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 06'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity07'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 07'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity08'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 08'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity09'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 09'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity10'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 10'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity11'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 11'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity12'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 12'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity13'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 13'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity14'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 14'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity15'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 15'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity16'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 16'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity17'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 17'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity18'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 18'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity19'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 19'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity20'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 20'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity21'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 21'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity22'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 22'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity23'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 23'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity24'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 24'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity25'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 25'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity26'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 26'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity27'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 27'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['quantity28'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Quantity 28'))
      ->setSettings(array(
        'precision' => 4,
        'scale' => 2,
      ))
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
    $fields['owner_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\association\Entity\Service::getCurrentUserId')
      ->setReadOnly(TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'));
// ----------------------------------------------------------------------------
    $weight++;
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
