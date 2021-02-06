<?php

namespace Drupal\association\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Member entities.
 *
 * @ingroup association
 */
interface MemberInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface
{

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Member designation.
   *
   * @return string
   *   Designation of the Member.
   */
  public function getDesignation();

  /**
   * Sets the Member designation.
   *
   * @param string $designation
   *   The Member designation.
   *
   * @return MemberInterface
   *   The called Member entity.
   */
  public function setDesignation($designation);

  /**
   * Gets the Member creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Member.
   */
  public function getCreatedTime();

  /**
   * Sets the Member creation timestamp.
   *
   * @param int $timestamp
   *   The Member creation timestamp.
   *
   * @return MemberInterface
   *   The called Member entity.
   */
  public function setCreatedTime($timestamp);

}
