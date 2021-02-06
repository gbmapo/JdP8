<?php

namespace Drupal\association;

use Drupal\association\Entity\PersonInterface;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Person entity.
 *
 * @see \Drupal\association\Entity\Person.
 */
class PersonAccessControlHandler extends EntityAccessControlHandler
{

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account)
  {
    /** @var PersonInterface $entity */
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view person entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit person entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete person entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL)
  {
    return AccessResult::allowedIfHasPermission($account, 'add person entities');
  }

}
