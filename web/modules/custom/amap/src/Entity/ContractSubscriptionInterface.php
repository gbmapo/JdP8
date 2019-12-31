<?php

namespace Drupal\amap\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Contract subscription entities.
 *
 * @ingroup amap
 */
interface ContractSubscriptionInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface
{

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Contract subscription creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Contract subscription.
   */
  public function getCreatedTime();

  /**
   * Sets the Contract subscription creation timestamp.
   *
   * @param int $timestamp
   *   The Contract subscription creation timestamp.
   *
   * @return \Drupal\amap\Entity\ContractSubscriptionInterface
   *   The called Contract subscription entity.
   */
  public function setCreatedTime($timestamp);

}
