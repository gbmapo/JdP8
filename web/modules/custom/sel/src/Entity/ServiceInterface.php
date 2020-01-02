<?php

namespace Drupal\sel\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Service entities.
 *
 * @ingroup sel
 */
interface ServiceInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface
{

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Service name.
   *
   * @return string
   *   Name of the Service.
   */
  public function getService();

  /**
   * Sets the Service name.
   *
   * @param string $service
   *   The Service name.
   *
   * @return \Drupal\sel\Entity\ServiceInterface
   *   The called Service entity.
   */
  public function setService($service);

  /**
   * Gets the Service creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Service.
   */
  public function getCreatedTime();

  /**
   * Sets the Service creation timestamp.
   *
   * @param int $timestamp
   *   The Service creation timestamp.
   *
   * @return \Drupal\sel\Entity\ServiceInterface
   *   The called Service entity.
   */
  public function setCreatedTime($timestamp);

}
