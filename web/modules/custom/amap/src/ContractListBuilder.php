<?php

namespace Drupal\amap;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Contract entities.
 *
 * @ingroup amap
 */
class ContractListBuilder extends EntityListBuilder
{


  /**
   * {@inheritdoc}
   */
  public function buildHeader()
  {
    $header['id'] = $this->t('Contract id');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {
    /* @var $entity \Drupal\amap\Entity\Contract */
    $row['id'] = $entity->id();
    $row['name'] = $entity->label();
    return $row + parent::buildRow($entity);
  }

}
