<?php

namespace Drupal\starwars\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Starship entities.
 *
 * @ingroup starwars
 */
interface StarshipInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Starship name.
   *
   * @return string
   *   Name of the Starship.
   */
  public function getName();

  /**
   * Sets the Starship name.
   *
   * @param string $name
   *   The Starship name.
   *
   * @return \Drupal\starwars\Entity\StarshipInterface
   *   The called Starship entity.
   */
  public function setName($name);

  /**
   * Gets the Starship creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Starship.
   */
  public function getCreatedTime();

  /**
   * Sets the Starship creation timestamp.
   *
   * @param int $timestamp
   *   The Starship creation timestamp.
   *
   * @return \Drupal\starwars\Entity\StarshipInterface
   *   The called Starship entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Starship published status indicator.
   *
   * Unpublished Starship are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Starship is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Starship.
   *
   * @param bool $published
   *   TRUE to set this Starship to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\starwars\Entity\StarshipInterface
   *   The called Starship entity.
   */
  public function setPublished($published);

  /**
   * Gets the Starship revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Starship revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\starwars\Entity\StarshipInterface
   *   The called Starship entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Starship revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Starship revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\starwars\Entity\StarshipInterface
   *   The called Starship entity.
   */
  public function setRevisionUserId($uid);

}
