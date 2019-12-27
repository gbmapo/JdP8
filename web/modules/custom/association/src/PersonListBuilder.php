<?php

namespace Drupal\association;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Person entities.
 *
 * @ingroup association
 */
class PersonListBuilder extends EntityListBuilder
{


  /**
   * {@inheritdoc}
   */
  public function buildHeader()
  {
    $header['id'] = $this->t('Person ID');
    $header['lastname'] = $this->t('Last Name');
    $header['firstname'] = $this->t('First Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {
    /* @var $entity \Drupal\association\Entity\Person */
    $row['id'] = $entity->id();
    $row['lastname'] = $entity->lastname->value;
    $row['firstname'] = $entity->firstname->value;
    return $row + parent::buildRow($entity);
  }

}
