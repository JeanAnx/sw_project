<?php

namespace Drupal\starwars;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\starwars\Entity\PlanetInterface;

/**
 * Defines the storage handler class for Planet entities.
 *
 * This extends the base storage class, adding required special handling for
 * Planet entities.
 *
 * @ingroup starwars
 */
interface PlanetStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Planet revision IDs for a specific Planet.
   *
   * @param \Drupal\starwars\Entity\PlanetInterface $entity
   *   The Planet entity.
   *
   * @return int[]
   *   Planet revision IDs (in ascending order).
   */
  public function revisionIds(PlanetInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Planet author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Planet revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\starwars\Entity\PlanetInterface $entity
   *   The Planet entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(PlanetInterface $entity);

  /**
   * Unsets the language for all Planet with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
