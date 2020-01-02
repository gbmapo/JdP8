<?php

namespace Drupal\sel;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Exchange entities.
 *
 * @ingroup sel
 */
class ExchangeListBuilder extends EntityListBuilder
{


  /**
   * {@inheritdoc}
   */
  public function buildHeader()
  {
    $header['id'] = $this->t('Exchange ID');
    $header['exchange'] = $this->t('Exchange');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity)
  {
    /* @var $entity \Drupal\sel\Entity\Exchange */
    $row['id'] = $entity->id();
    $row['exchange'] = Link::createFromRoute(
      $entity->label(),
      'entity.exchange.edit_form',
      ['exchange' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
