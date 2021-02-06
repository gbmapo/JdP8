<?php

namespace Drupal\association;

use Drupal\association\Entity\Member;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Member entities.
 *
 * @ingroup association
 */
class MemberListBuilder extends EntityListBuilder
{


  /**
   * {@inheritdoc}
   */
  public function buildHeader()
  {
    $header['id'] = $this->t('Member ID');
    $header['designation'] = $this->t('Designation');
    $header['status'] = $this->t('Status');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {
    /* @var $entity Member */
    $row['id'] = $entity->id();
    $row['designation'] = $entity->designation->value;
    $row['status'] = $entity->status->value;
    return $row + parent::buildRow($entity);
  }

}
