<?php

namespace Drupal\starwars\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Planet entities.
 *
 * @ingroup starwars
 */
interface PlanetInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Planet name.
   *
   * @return string
   *   Name of the Planet.
   */
  public function getName();

  /**
   * Sets the Planet name.
   *
   * @param string $name
   *   The Planet name.
   *
   * @return \Drupal\starwars\Entity\PlanetInterface
   *   The called Planet entity.
   */
  public function setName($name);

  /**
   * Gets the Planet creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Planet.
   */
  public function getCreatedTime();

  /**
   * Sets the Planet creation timestamp.
   *
   * @param int $timestamp
   *   The Planet creation timestamp.
   *
   * @return \Drupal\starwars\Entity\PlanetInterface
   *   The called Planet entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Planet published status indicator.
   *
   * Unpublished Planet are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Planet is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Planet.
   *
   * @param bool $published
   *   TRUE to set this Planet to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\starwars\Entity\PlanetInterface
   *   The called Planet entity.
   */
  public function setPublished($published);

  /**
   * Gets the Planet revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Planet revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\starwars\Entity\PlanetInterface
   *   The called Planet entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Planet revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Planet revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\starwars\Entity\PlanetInterface
   *   The called Planet entity.
   */
  public function setRevisionUserId($uid);

}
