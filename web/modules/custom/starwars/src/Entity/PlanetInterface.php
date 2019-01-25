<?php

namespace Drupal\starwars\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining starwars_planet entities.
 *
 * @ingroup starwars
 */
interface PlanetInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the starwars_planet name.
   *
   * @return string
   *   Name of the starwars_planet.
   */
  public function getName();

  /**
   * Sets the starwars_planet name.
   *
   * @param string $name
   *   The starwars_planet name.
   *
   * @return \Drupal\starwars\Entity\PlanetInterface
   *   The called starwars_planet entity.
   */
  public function setName($name);

  /**
   * Gets the starwars_planet creation timestamp.
   *
   * @return int
   *   Creation timestamp of the starwars_planet.
   */
  public function getCreatedTime();

  /**
   * Sets the starwars_planet creation timestamp.
   *
   * @param int $timestamp
   *   The starwars_planet creation timestamp.
   *
   * @return \Drupal\starwars\Entity\PlanetInterface
   *   The called starwars_planet entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the starwars_planet published status indicator.
   *
   * Unpublished starwars_planet are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the starwars_planet is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a starwars_planet.
   *
   * @param bool $published
   *   TRUE to set this starwars_planet to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\starwars\Entity\PlanetInterface
   *   The called starwars_planet entity.
   */
  public function setPublished($published);

  /**
   * Gets the starwars_planet revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the starwars_planet revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\starwars\Entity\PlanetInterface
   *   The called starwars_planet entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the starwars_planet revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the starwars_planet revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\starwars\Entity\PlanetInterface
   *   The called starwars_planet entity.
   */
  public function setRevisionUserId($uid);

}
