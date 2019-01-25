<?php

namespace Drupal\starwars;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class PeopleEntityStorage extends SqlContentEntityStorage implements PeopleEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(PeopleEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {people_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {people_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(PeopleEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {people_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('people_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
