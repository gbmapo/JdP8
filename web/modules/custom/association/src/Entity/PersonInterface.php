<?php

namespace Drupal\association\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Person entities.
 *
 * @ingroup association
 */
interface PersonInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface
{

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Person Last Name.
   *
   * @return string
   *   Last name of the Person.
   */
  public function getLastname();

  /**
   * Sets the Person Last Name.
   *
   * @param string $lastname
   *   The Person last name.
   *
   * @return PersonInterface
   *   The called Person entity.
   */
  public function setLastname($lastname);

  /**
   * Gets the Person creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Person.
   */
  public function getCreatedTime();

  /**
   * Sets the Person creation timestamp.
   *
   * @param int $timestamp
   *   The Person creation timestamp.
   *
   * @return PersonInterface
   *   The called Person entity.
   */
  public function setCreatedTime($timestamp);

}
