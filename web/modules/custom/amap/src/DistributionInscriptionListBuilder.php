<?php

namespace Drupal\amap;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Distribution inscription entities.
 *
 * @ingroup amap
 */
class DistributionInscriptionListBuilder extends EntityListBuilder
{


  /**
   * {@inheritdoc}
   */
  public function buildHeader()
  {
    $header['id'] = $this->t('Distribution inscription ID');
    $header['distributiondate_id'] = $this->t('Distribution date');
    $header['amapien_id'] = $this->t('AMAPien');
    $header['role'] = $this->t('Role');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {
    /* @var $entity \Drupal\amap\Entity\DistributionInscription */
    $row['id'] = $entity->id();
    $row['distributiondate_id'] = $entity->distributiondate_id->target_id;
    $row['amapien_id'] = $entity->amapien_id->target_id;
    $row['role'] = $entity->role->value;
    return $row + parent::buildRow($entity);
  }

}
