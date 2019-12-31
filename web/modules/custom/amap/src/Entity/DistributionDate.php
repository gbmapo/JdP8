<?php

namespace Drupal\amap\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Distribution date entity.
 *
 * @ingroup amap
 *
 * @ContentEntityType(
 *   id = "distribution_date",
 *   label = @Translation("Distribution date"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\amap\DistributionDateListBuilder",
 *     "views_data" = "Drupal\amap\Entity\DistributionDateViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\amap\Form\DistributionDateForm",
 *       "add" = "Drupal\amap\Form\DistributionDateForm",
 *       "edit" = "Drupal\amap\Form\DistributionDateForm",
 *       "delete" = "Drupal\amap\Form\DistributionDateDeleteForm",
 *     },
 *     "access" = "Drupal\amap\DistributionDateAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\amap\DistributionDateHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "distribution_date",
 *   admin_permission = "administer distribution date entities",
 *   entity_keys = {
 *     "id" = "id",
 *   },
 *   links = {
 *     "canonical" = "/amap/distribution_date/{distribution_date}",
 *     "add-form" = "/amap/distribution_date/add",
 *     "edit-form" = "/amap/distribution_date/{distribution_date}/edit",
 *     "delete-form" = "/amap/distribution_date/{distribution_date}/delete",
 *     "collection" = "/amap/distribution_date",
 *   },
 *   field_ui_base_route = "distribution_date.settings"
 * )
 */
class DistributionDate extends ContentEntityBase implements DistributionDateInterface
{

  use EntityChangedTrait;

// ----------------------------------------------------------------------------

  /**
   * {@inheritdoc}
   */
  public function label()
  {
    $label = $this->get('distributiondate')->value;
    return $label;
  }

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
    $fields['distributiondate'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Distribution date'))
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
    $fields['product01'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 01'))
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
    $fields['product02'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 02'))
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
    $fields['product03'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 03'))
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
    $fields['product04'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 04'))
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
    $fields['product05'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 05'))
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
    $fields['product06'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 06'))
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
    $fields['product07'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 07'))
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
    $fields['product08'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 08'))
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
    $fields['product09'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 09'))
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
    $fields['product10'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 10'))
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
    $fields['product11'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 11'))
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
    $fields['product12'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 12'))
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
    $fields['product13'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 13'))
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
    $fields['product14'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 14'))
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
    $fields['product15'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 15'))
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
    $fields['product16'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 16'))
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
    $fields['product17'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 17'))
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
    $fields['product18'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 18'))
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
    $fields['product19'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 19'))
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
    $fields['product20'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Product 20'))
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
    $fields['numberofproducts'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Number of products'))
      ->setReadOnly(TRUE);
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
