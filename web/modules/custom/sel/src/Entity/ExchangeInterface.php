<?php

namespace Drupal\sel\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Exchange entities.
 *
 * @ingroup sel
 */
interface ExchangeInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface
{

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Exchange name.
   *
   * @return string
   *   Name of the Exchange.
   */
  public function getExchange();

  /**
   * Sets the Exchange name.
   *
   * @param string $name
   *   The Exchange name.
   *
   * @return \Drupal\sel\Entity\ExchangeInterface
   *   The called Exchange entity.
   */
  public function setExchange($exchange);

  /**
   * Gets the Exchange creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Exchange.
   */
  public function getCreatedTime();

  /**
   * Sets the Exchange creation timestamp.
   *
   * @param int $timestamp
   *   The Exchange creation timestamp.
   *
   * @return \Drupal\sel\Entity\ExchangeInterface
   *   The called Exchange entity.
   */
  public function setCreatedTime($timestamp);

}
