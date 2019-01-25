<?php

namespace Drupal\starwars\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for People entity entities.
 */
class PeopleEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
