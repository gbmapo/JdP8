<?php

namespace Drupal\amap\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Distribution date entities.
 *
 * @ingroup amap
 */
interface DistributionDateInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface
{

  // Add get/set methods for your configuration properties here.


  /**
   * Gets the Distribution date creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Distribution date.
   */
  public function getCreatedTime();

  /**
   * Sets the Distribution date creation timestamp.
   *
   * @param int $timestamp
   *   The Distribution date creation timestamp.
   *
   * @return \Drupal\amap\Entity\DistributionDateInterface
   *   The called Distribution date entity.
   */
  public function setCreatedTime($timestamp);

}
