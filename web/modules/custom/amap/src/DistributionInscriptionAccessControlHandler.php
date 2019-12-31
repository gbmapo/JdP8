<?php

namespace Drupal\amap;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Distribution inscription entity.
 *
 * @see \Drupal\amap\Entity\DistributionInscription.
 */
class DistributionInscriptionAccessControlHandler extends EntityAccessControlHandler
{

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account)
  {
    /** @var \Drupal\amap\Entity\DistributionInscriptionInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view distribution inscription entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit distribution inscription entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete distribution inscription entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL)
  {
    return AccessResult::allowedIfHasPermission($account, 'add distribution inscription entities');
  }

}
