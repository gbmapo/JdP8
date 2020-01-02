<?php

namespace Drupal\sel;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Service entities.
 *
 * @ingroup sel
 */
class ServiceListBuilder extends EntityListBuilder
{


  /**
   * {@inheritdoc}
   */
  public function buildHeader()
  {
    $header['id'] = $this->t('Service ID');
    $header['service'] = $this->t('Service');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {
    /* @var $entity \Drupal\sel\Entity\Service */
    $row['id'] = $entity->id();
    $row['service'] = Link::createFromRoute(
      $entity->label(),
      'entity.service.edit_form',
      ['service' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
