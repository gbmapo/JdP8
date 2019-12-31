<?php

namespace Drupal\amap;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Contract type entities.
 *
 * @ingroup amap
 */
class ContractTypeListBuilder extends EntityListBuilder
{


  /**
   * {@inheritdoc}
   */
  public function buildHeader()
  {
    $header['id'] = $this->t('Contract type id');
    $header['name'] = $this->t('Name');
    $header['displayedname'] = $this->t('Displayed Name');
    $header['numberofquantities'] = $this->t('Number of quantities');
    $header['formheader'] = $this->t('Form header');
    $header['exportheader'] = $this->t('Export header');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {
    /* @var $entity \Drupal\amap\Entity\ContractType */
    $row['id'] = $entity->id();
    $row['name'] = $entity->name->value;
    $row['displayedname'] = $entity->displayedname->value;
    $row['numberofquantities'] = $entity->numberofquantities->value;
    $row['formheader'] = $entity->formheader->value;
    $row['exportheader'] = $entity->exportheader->value;
    return $row + parent::buildRow($entity);
  }

}
