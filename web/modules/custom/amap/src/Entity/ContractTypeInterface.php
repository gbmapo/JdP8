<?php

namespace Drupal\amap\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface for defining Contract type entities.
 *
 * @ingroup amap
 */
interface ContractTypeInterface extends ContentEntityInterface, EntityChangedInterface
{

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Contract type name.
   *
   * @return string
   *   Name of the Contract type.
   */
  public function getName();

  /**
   * Sets the Contract type name.
   *
   * @param string $name
   *   The Contract type name.
   *
   * @return \Drupal\amap\Entity\ContractTypeInterface
   *   The called Contract type entity.
   */
  public function setName($name);

  /**
   * Gets the Contract type creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Contract type.
   */
  public function getCreatedTime();

  /**
   * Sets the Contract type creation timestamp.
   *
   * @param int $timestamp
   *   The Contract type creation timestamp.
   *
   * @return \Drupal\amap\Entity\ContractTypeInterface
   *   The called Contract type entity.
   */
  public function setCreatedTime($timestamp);

}
