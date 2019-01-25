<?php

namespace Drupal\starwars;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\starwars\Entity\StarshipInterface;

/**
 * Defines the storage handler class for Starship entities.
 *
 * This extends the base storage class, adding required special handling for
 * Starship entities.
 *
 * @ingroup starwars
 */
interface StarshipStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Starship revision IDs for a specific Starship.
   *
   * @param \Drupal\starwars\Entity\StarshipInterface $entity
   *   The Starship entity.
   *
   * @return int[]
   *   Starship revision IDs (in ascending order).
   */
  public function revisionIds(StarshipInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Starship author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Starship revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\starwars\Entity\StarshipInterface $entity
   *   The Starship entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(StarshipInterface $entity);

  /**
   * Unsets the language for all Starship with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
