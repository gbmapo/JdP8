<?php

namespace Drupal\sel\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Service category entities.
 *
 * @ingroup sel
 */
interface ServiceCategoryInterface extends ContentEntityInterface, EntityChangedInterface
{

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Service category name.
   *
   * @return string
   *   Name of the Service category.
   */
  public function getName();

  /**
   * Sets the Service category name.
   *
   * @param string $name
   *   The Service category name.
   *
   * @return \Drupal\sel\Entity\ServiceCategoryInterface
   *   The called Service category entity.
   */
  public function setName($name);

  /**
   * Gets the Service category creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Service category.
   */
  public function getCreatedTime();

  /**
   * Sets the Service category creation timestamp.
   *
   * @param int $timestamp
   *   The Service category creation timestamp.
   *
   * @return \Drupal\sel\Entity\ServiceCategoryInterface
   *   The called Service category entity.
   */
  public function setCreatedTime($timestamp);

}
