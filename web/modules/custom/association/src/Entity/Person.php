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
 * Defines the Person entity.
 *
 * @ingroup association
 *
 * @ContentEntityType(
 *   id = "person",
 *   label = @Translation("Person"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\association\PersonListBuilder",
 *     "views_data" = "Drupal\association\Entity\PersonViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\association\Form\PersonForm",
 *       "add" = "Drupal\association\Form\PersonForm",
 *       "edit" = "Drupal\association\Form\PersonForm",
 *       "delete" = "Drupal\association\Form\PersonDeleteForm",
 *     },
 *     "access" = "Drupal\association\PersonAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\association\PersonHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "person",
 *   admin_permission = "administer person entities",
 *   entity_keys = {
 *     "id" = "id",
 *   },
 *   links = {
 *     "canonical" = "/association/person/{person}",
 *     "add-form" = "/association/person/add",
 *     "edit-form" = "/association/person/{person}/edit",
 *     "delete-form" = "/association/person/{person}/delete",
 *     "collection" = "/association/person",
 *   },
 *   field_ui_base_route = "person.settings"
 * )
 */
class Person extends ContentEntityBase implements PersonInterface
{

  use EntityChangedTrait;

// ----------------------------------------------------------------------------

  /**
   * {@inheritdoc}
   */
  public function label()
  {
    $label = $this->get('lastname')->value . " " . $this->get('firstname')->value;
    return $label;
  }

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
  public function getLastname()
  {
    return $this->get('lastname')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setLastname($lastname)
  {
    $this->set('lastname', $lastname);
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
    $fields['lastname'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Last Name'))
      ->setRequired(TRUE)
      ->setSettings([
        'max_length'      => 50,
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
    $fields['firstname'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First Name'))
      ->setRequired(TRUE)
      ->setSettings([
        'max_length'      => 50,
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
    $fields['cellphone'] = BaseFieldDefinition::create('telephone')
      ->setLabel(t('Cellphone'))
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
    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email Address'))
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label'  => 'above',
        'type'   => 'string',
        'weight' => $weight,
      ])
      ->setReadOnly(TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['iscontact'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Contact?'))
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('view', [
        'settings' => [
          'format' => 'yes-no',
        ],
        'weight'   => $weight,
      ])
      ->setDisplayOptions('form', [
        'settings' => [
          'display_label' => TRUE,
        ],
        'type'     => 'boolean_checkbox',
        'weight'   => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['isactive'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Active?'))
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('view', [
        'settings' => [
          'format' => 'yes-no',
        ],
        'weight'   => $weight,
      ])
      ->setDisplayOptions('form', [
        'settings' => [
          'display_label' => TRUE,
        ],
        'type'     => 'boolean_checkbox',
        'weight'   => $weight,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['member_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Member'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'member')
      ->setDisplayOptions('view', [
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type'     => 'entity_reference_autocomplete',
        'weight'   => $weight,
        'settings' => [
          'match_operator'    => 'CONTAINS',
          'size'              => '60',
          'autocomplete_type' => 'tags',
          'placeholder'       => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
// ----------------------------------------------------------------------------
    $weight++;
    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User'))
      ->setRequired(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'weight' => $weight,
      ])
      ->setDisplayOptions('form', [
        'type'     => 'entity_reference_autocomplete',
        'weight'   => $weight,
        'settings' => [
          'match_operator'    => 'CONTAINS',
          'size'              => '60',
          'autocomplete_type' => 'tags',
          'placeholder'       => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
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
