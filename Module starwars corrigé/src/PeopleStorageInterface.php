<?php

namespace Drupal\starwars;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\starwars\Entity\PeopleInterface;

/**
 * Defines the storage handler class for People entities.
 *
 * This extends the base storage class, adding required special handling for
 * People entities.
 *
 * @ingroup starwars
 */
interface PeopleStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of People revision IDs for a specific People.
   *
   * @param \Drupal\starwars\Entity\PeopleInterface $entity
   *   The People entity.
   *
   * @return int[]
   *   People revision IDs (in ascending order).
   */
  public function revisionIds(PeopleInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as People author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   People revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\starwars\Entity\PeopleInterface $entity
   *   The People entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(PeopleInterface $entity);

  /**
   * Unsets the language for all People with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
