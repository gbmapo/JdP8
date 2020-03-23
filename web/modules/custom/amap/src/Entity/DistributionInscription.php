<?php

namespace Drupal\amap\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Distribution inscription entity.
 *
 * @ingroup amap
 *
 * @ContentEntityType(
 *   id = "distribution_inscription",
 *   label = @Translation("Distribution inscription"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\amap\DistributionInscriptionListBuilder",
 *     "views_data" = "Drupal\amap\Entity\DistributionInscriptionViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\amap\Form\DistributionInscriptionForm",
 *       "add" = "Drupal\amap\Form\DistributionInscriptionForm",
 *       "edit" = "Drupal\amap\Form\DistributionInscriptionForm",
 *       "delete" = "Drupal\amap\Form\DistributionInscriptionDeleteForm",
 *     },
 *     "access" = "Drupal\amap\DistributionInscriptionAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\amap\DistributionInscriptionHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "distribution_inscription",
 *   admin_permission = "administer distribution inscription entities",
 *   entity_keys = {
 *     "id" = "id",
 *   },
 *   links = {
 *     "canonical" = "/amap/distribution_inscription/{distribution_inscription}",
 *     "add-form" = "/amap/distribution_inscription/add",
 *     "edit-form" = "/amap/distribution_inscription/{distribution_inscription}/edit",
 *     "delete-form" = "/amap/distribution_inscription/{distribution_inscription}/delete",
 *     "collection" = "/amap/distribution_inscription",
 *   },
 *   field_ui_base_route = "distribution_inscription.settings"
 * )
 */
class DistributionInscription extends ContentEntityBase implements DistributionInscriptionInterface
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
    $fields['distributiondate_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Distribution Date ID'))
      ->setSetting('target_type', 'distribution_date')
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
    $fields['amapien_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('AMAPien ID'))
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
    $fields['role'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Role'))
      ->setRequired(TRUE)
      ->setSettings(['allowed_values_function' => 'amap_allowed_values_function'])
      ->setDefaultValue('D')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'weight' => $weight,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
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
