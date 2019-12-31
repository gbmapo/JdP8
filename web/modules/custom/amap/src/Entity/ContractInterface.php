<?php

namespace Drupal\amap\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Contract entities.
 *
 * @ingroup amap
 */
interface ContractInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface
{

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Contract name.
   *
   * @return string
   *   Name of the Contract.
   */
  public function getName();

  /**
   * Sets the Contract name.
   *
   * @param string $name
   *   The Contract name.
   *
   * @return \Drupal\amap\Entity\ContractInterface
   *   The called Contract entity.
   */
  public function setName($name);

  /**
   * Gets the Contract creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Contract.
   */
  public function getCreatedTime();

  /**
   * Sets the Contract creation timestamp.
   *
   * @param int $timestamp
   *   The Contract creation timestamp.
   *
   * @return \Drupal\amap\Entity\ContractInterface
   *   The called Contract entity.
   */
  public function setCreatedTime($timestamp);

}
