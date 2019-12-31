<?php

namespace Drupal\amap;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Contract subscription entity.
 *
 * @see \Drupal\amap\Entity\ContractSubscription.
 */
class ContractSubscriptionAccessControlHandler extends EntityAccessControlHandler
{

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account)
  {
    /** @var \Drupal\amap\Entity\ContractSubscriptionInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view contract subscription entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit contract subscription entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete contract subscription entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL)
  {
    return AccessResult::allowedIfHasPermission($account, 'add contract subscription entities');
  }

}
