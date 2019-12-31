<?php

namespace Drupal\amap\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Distribution date entities.
 */
class DistributionDateViewsData extends EntityViewsData
{

  /**
   * {@inheritdoc}
   */
  public function getViewsData()
  {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    $data['distribution_date']['distributiondate']['filter'] = [
      'field' => 'distributiondate',
      'table' => 'distribution_date',
      'id' => 'datetime',
      'field_name' => 'distributiondate',
      'entity_type' => 'distribution_date',
      'allow empty' => TRUE,
    ];

    return $data;
  }

}
