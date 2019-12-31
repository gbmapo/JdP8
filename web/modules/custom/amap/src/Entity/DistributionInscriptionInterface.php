<?php

namespace Drupal\amap\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Distribution inscription entities.
 *
 * @ingroup amap
 */
interface DistributionInscriptionInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface
{

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Distribution inscription creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Distribution inscription.
   */
  public function getCreatedTime();

  /**
   * Sets the Distribution inscription creation timestamp.
   *
   * @param int $timestamp
   *   The Distribution inscription creation timestamp.
   *
   * @return \Drupal\amap\Entity\DistributionInscriptionInterface
   *   The called Distribution inscription entity.
   */
  public function setCreatedTime($timestamp);

}
