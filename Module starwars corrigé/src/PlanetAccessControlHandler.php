<?php

namespace Drupal\starwars;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Planet entity.
 *
 * @see \Drupal\starwars\Entity\Planet.
 */
class PlanetAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\starwars\Entity\PlanetInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished planet entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published planet entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit planet entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete planet entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add planet entities');
  }

}
