<?php

namespace Drupal\association\Entity;

use Drupal;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Member entity.
 *
 * @ingroup association
 *
 * @ContentEntityType(
 *   id = "member",
 *   label = @Translation("Member"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\association\MemberListBuilder",
 *     "views_data" = "Drupal\association\Entity\MemberViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\association\Form\MemberForm",
 *       "add" = "Drupal\association\Form\MemberForm",
 *       "edit" = "Drupal\association\Form\MemberForm",
 *       "delete" = "Drupal\association\Form\MemberDeleteForm",
 *     },
 *     "access" = "Drupal\association\MemberAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\association\MemberHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "member",
 *   admin_permission = "administer member entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "designation",
 *   },
 *   links = {
 *     "canonical" = "/association/member/{member}",
 *     "add-form" = "/association/member/add",
 *     "edit-form" = "/association/member/{member}/edit",
 *     "delete-form" = "/association/member/{member}/delete",
 *     "collection" = "/association/member",
 *   },
 *   field_ui_base_route = "member.settings"
 * )
 */
class Member extends ContentEntityBase implements MemberInterface
{

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values)
  {
    parent::preCreate($storage_controller, $values);
    $values += [
      'owner_id' => Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getDesignation()
  {
    return $this->get('designation')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setDesignation($designation)
  {
    $this->set('designation', $designation);
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
    $fields['designation'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Designation'))
      ->setSettings([
        'max_length'      => 128,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label'  => 'above',
        'type'   => 'string',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type'   => 'string_textfield',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['addresssupplement'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Address supplement'))
      ->setSettings([
        'max_length'      => 128,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label'  => 'above',
        'type'   => 'string',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type'   => 'string_textfield',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['street'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Street'))
      ->setSettings([
        'max_length'      => 128,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label'  => 'above',
        'type'   => 'string',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type'   => 'string_textfield',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['postalcode'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Postal Code'))
      ->setSettings([
        'max_length'      => 10,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label'  => 'above',
        'type'   => 'string',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type'   => 'string_textfield',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['city'] = BaseFieldDefinition::create('string')
      ->setLabel(t('City'))
      ->setSettings([
        'max_length'      => 128,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label'  => 'above',
        'type'   => 'string',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type'   => 'string_textfield',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
//     $weight++;
// //  $fields['administrativearea'] = BaseFieldDefinition::create('string')
//     $fields['administrativearea'] = BaseFieldDefinition::create('list_string')
//       ->setLabel(t('Administrative area'))
// //       ->setSettings(array(
// //         'max_length' => 30,
// //         'text_processing' => 0,
// //       ))
//       ->setSettings(['allowed_values_function' => 'association_allowed_values_function'])
//       ->setDefaultValue('')
//       ->setDisplayOptions('view', array(
//         'label' => 'above',
//         'type' => 'string',
//         'weight' => $weight,
//       ))
//       ->setDisplayOptions('form', array(
//         'type' => 'string_textfield',
//         'weight' => $weight,
//       ))
//       ->setDisplayConfigurable('form', TRUE)
//       ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['country'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Country'))
      ->setSettings(['allowed_values_function' => 'association_allowed_values_function'])
      ->setDefaultValue('FR')
      ->setDisplayOptions('view', [
        'label'  => 'above',
        'type'   => 'string',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type'   => 'options_select',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['telephone'] = BaseFieldDefinition::create('telephone')
      ->setLabel(t('Phone'))
      ->setSettings([
        'max_length'      => 10,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label'  => 'above',
        'type'   => 'telephone_default',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type'   => 'telephone_default',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['status'] = BaseFieldDefinition::create('list_integer')
      ->setLabel(t('Status'))
      ->setRequired(TRUE)
      ->setSettings(['allowed_values_function' => 'association_allowed_values_function'])
      ->setDefaultValue(2)
      ->setDisplayOptions('view', [
        'label'  => 'above',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type'   => 'options_select',
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['startdate'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Membership Start Date'))
      ->setSetting('datetime_type', 'date')
      ->setDefaultValue([0 => [
        'default_date_type' => 'now',
        'default_date'      => 'now',
      ]])
      ->setDisplayOptions('view', [
        'label'  => 'above',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['enddate'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Membership End Date'))
      ->setSetting('datetime_type', 'date')
      ->setDefaultValue([0 => [
        'default_date_type' => 'now',
        'default_date'      => '2037-12-31',
      ]])
      ->setDisplayOptions('view', [
        'label'  => 'above',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'weight' => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['contact_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Contact'))
      ->setDisplayOptions('view', [
        'weight' => $weight,
      ])
      ->setReadOnly(TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['comment'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Comment'))
      ->setSettings([
        'max_length'      => 1024,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label'  => 'above',
        'type'   => 'string_long',
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type'   => 'string_textarea',
        'weight' => $weight,
      ])
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
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));
// ----------------------------------------------------------------------------
    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));
// ----------------------------------------------------------------------------
    return $fields;
  }

  public static function getCurrentUserId()
  {
    return [Drupal::currentUser()->id()];
  }
}
