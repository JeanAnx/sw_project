<?php

namespace Drupal\starwars;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class StarshipStorage extends SqlContentEntityStorage implements StarshipStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(StarshipInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {starwars_starship_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {starwars_starship_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(StarshipInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {starwars_starship_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('starwars_starship_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
