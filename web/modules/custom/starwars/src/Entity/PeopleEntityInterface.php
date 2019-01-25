<?php

namespace Drupal\starwars\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining People entity entities.
 *
 * @ingroup starwars
 */
interface PeopleEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the People entity name.
   *
   * @return string
   *   Name of the People entity.
   */
  public function getName();

  /**
   * Sets the People entity name.
   *
   * @param string $name
   *   The People entity name.
   *
   * @return \Drupal\starwars\Entity\PeopleEntityInterface
   *   The called People entity entity.
   */
  public function setName($name);

  /**
   * Gets the People entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the People entity.
   */
  public function getCreatedTime();

  /**
   * Sets the People entity creation timestamp.
   *
   * @param int $timestamp
   *   The People entity creation timestamp.
   *
   * @return \Drupal\starwars\Entity\PeopleEntityInterface
   *   The called People entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the People entity published status indicator.
   *
   * Unpublished People entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the People entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a People entity.
   *
   * @param bool $published
   *   TRUE to set this People entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\starwars\Entity\PeopleEntityInterface
   *   The called People entity entity.
   */
  public function setPublished($published);

  /**
   * Gets the People entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the People entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\starwars\Entity\PeopleEntityInterface
   *   The called People entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the People entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the People entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\starwars\Entity\PeopleEntityInterface
   *   The called People entity entity.
   */
  public function setRevisionUserId($uid);

}
