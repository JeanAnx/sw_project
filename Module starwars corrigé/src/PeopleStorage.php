<?php

namespace Drupal\starwars;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class PeopleStorage extends SqlContentEntityStorage implements PeopleStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(PeopleInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {starwars_people_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {starwars_people_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(PeopleInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {starwars_people_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('starwars_people_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
