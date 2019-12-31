<?php

namespace Drupal\amap;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Distribution date entity.
 *
 * @see \Drupal\amap\Entity\DistributionDate.
 */
class DistributionDateAccessControlHandler extends EntityAccessControlHandler
{

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account)
  {
    /** @var \Drupal\amap\Entity\DistributionDateInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view distribution date entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit distribution date entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete distribution date entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL)
  {
    return AccessResult::allowedIfHasPermission($account, 'add distribution date entities');
  }

}
