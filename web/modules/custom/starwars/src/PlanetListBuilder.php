<?php

namespace Drupal\starwars;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of starwars_planet entities.
 *
 * @ingroup starwars
 */
class PlanetListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('starwars_planet ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\starwars\Entity\Planet */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.starwars_planet.edit_form',
      ['starwars_planet' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
