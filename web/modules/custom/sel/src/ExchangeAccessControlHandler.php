<?php

namespace Drupal\sel;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Exchange entity.
 *
 * @see \Drupal\sel\Entity\Exchange.
 */
class ExchangeAccessControlHandler extends EntityAccessControlHandler
{

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account)
  {
    /** @var \Drupal\sel\Entity\ExchangeInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view exchange entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit exchange entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete exchange entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL)
  {
    return AccessResult::allowedIfHasPermission($account, 'add exchange entities');
  }

}
