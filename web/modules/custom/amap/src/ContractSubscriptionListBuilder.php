<?php

namespace Drupal\amap;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Contract subscription entities.
 *
 * @ingroup amap
 */
class ContractSubscriptionListBuilder extends EntityListBuilder
{


  /**
   * {@inheritdoc}
   */
  public function buildHeader()
  {
    $header['id'] = $this->t('Contract subscription id');
    $header['contract_id'] = $this->t('Contract id');
    $header['member_id'] = $this->t('Member id');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {
    /* @var $entity \Drupal\amap\Entity\ContractSubscription */
    $row['id'] = $entity->id();
    $row['contract_id'] = $entity->contract_id->target_id;
    $row['member_id'] = $entity->member_id->target_id;
    return $row + parent::buildRow($entity);
  }

}
