<?php

namespace Drupal\starwars;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the starwars_planet entity.
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
          return AccessResult::allowedIfHasPermission($account, 'view unpublished starwars_planet entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published starwars_planet entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit starwars_planet entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete starwars_planet entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add starwars_planet entities');
  }

}
