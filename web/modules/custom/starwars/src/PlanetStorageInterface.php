<?php

namespace Drupal\starwars;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\starwars\Entity\PlanetInterface;

/**
 * Defines the storage handler class for starwars_planet entities.
 *
 * This extends the base storage class, adding required special handling for
 * starwars_planet entities.
 *
 * @ingroup starwars
 */
interface PlanetStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of starwars_planet revision IDs for a specific starwars_planet.
   *
   * @param \Drupal\starwars\Entity\PlanetInterface $entity
   *   The starwars_planet entity.
   *
   * @return int[]
   *   starwars_planet revision IDs (in ascending order).
   */
  public function revisionIds(PlanetInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as starwars_planet author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   starwars_planet revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\starwars\Entity\PlanetInterface $entity
   *   The starwars_planet entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(PlanetInterface $entity);

  /**
   * Unsets the language for all starwars_planet with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
