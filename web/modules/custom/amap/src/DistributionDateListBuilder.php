<?php

namespace Drupal\amap;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Distribution date entities.
 *
 * @ingroup amap
 */
class DistributionDateListBuilder extends EntityListBuilder
{


  /**
   * {@inheritdoc}
   */
  public function buildHeader()
  {
    $header['id'] = $this->t('Distribution date ID');
    $header['distributiondate'] = $this->t('Distribution date');
    $header['numberofproducts'] = $this->t('Number of products');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {
    /* @var $entity \Drupal\amap\Entity\DistributionDate */
    $row['id'] = $entity->id();
    $row['distributiondate'] = $entity->distributiondate->value;
    $row['numberofproducts'] = $entity->numberofproducts->value;
    return $row + parent::buildRow($entity);
  }

}
