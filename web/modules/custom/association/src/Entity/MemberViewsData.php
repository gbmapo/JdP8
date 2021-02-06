<?php

namespace Drupal\association\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Member entities.
 */
class MemberViewsData extends EntityViewsData
{

  /**
   * {@inheritdoc}
   */
  public function getViewsData()
  {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    $data['member']['contact_id'] = [
      'title'        => t('Contact Id'),
      'relationship' => [
        'base'       => 'person',
        'base field' => 'id',
        'handler'    => 'views_handler_relationship',
        'label'      => t('Contact'),
        'title'      => t('Contact'),
        'id'         => 'standard',
      ],
    ];

    return $data;
  }

}
