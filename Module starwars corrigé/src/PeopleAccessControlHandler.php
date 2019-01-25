<?php

namespace Drupal\starwars;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the People entity.
 *
 * @see \Drupal\starwars\Entity\People.
 */
class PeopleAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\starwars\Entity\PeopleInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished people entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published people entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit people entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete people entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add people entities');
  }

}
