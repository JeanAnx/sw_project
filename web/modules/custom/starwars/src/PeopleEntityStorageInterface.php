<?php

namespace Drupal\starwars;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\starwars\Entity\PeopleEntityInterface;

/**
 * Defines the storage handler class for People entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * People entity entities.
 *
 * @ingroup starwars
 */
interface PeopleEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of People entity revision IDs for a specific People entity.
   *
   * @param \Drupal\starwars\Entity\PeopleEntityInterface $entity
   *   The People entity entity.
   *
   * @return int[]
   *   People entity revision IDs (in ascending order).
   */
  public function revisionIds(PeopleEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as People entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   People entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\starwars\Entity\PeopleEntityInterface $entity
   *   The People entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(PeopleEntityInterface $entity);

  /**
   * Unsets the language for all People entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
