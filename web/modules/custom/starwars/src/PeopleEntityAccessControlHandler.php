<?php

namespace Drupal\starwars;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the People entity entity.
 *
 * @see \Drupal\starwars\Entity\PeopleEntity.
 */
class PeopleEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\starwars\Entity\PeopleEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished people entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published people entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit people entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete people entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add people entity entities');
  }

}
