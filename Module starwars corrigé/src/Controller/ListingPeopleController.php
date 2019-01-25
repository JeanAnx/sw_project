<?php

namespace Drupal\starwars\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class ListingPeopleController.
 */
class ListingPeopleController extends ControllerBase {

  /**
   * Listing des personnages
   *
   * @return array
   *
   */
  public function listing() {


    /**
     * On récupère les entités People
     */
    $ids = \Drupal::entityQuery('starwars_people')
      ->execute();
    $controller = \Drupal::entityTypeManager()->getStorage('starwars_people');
    $entities = $controller->loadMultiple($ids);


    $peoples = [];

    // Parcours des entités
    /* @var \Drupal\starwars\Entity\People $entity */
    foreach ($entities as $entity) {

      // On récupère l'espèce
      $specie = $entity->get('field_species')->referencedEntities();

      // On récupère la planète associée
      $planet = $entity->get('field_homeworld')->referencedEntities();

      // On récupère les terrains associés à la planète
      $refTerrains = $planet[0]->get('field_terrain')->referencedEntities();
      $terrains = [];
      foreach ($refTerrains as $terrain) {
        $terrains[] = $terrain->getName();
      }

      // On récupère les climats associés à la planète
      $refClimats = $planet[0]->get('field_climat')->referencedEntities();
      $climats = [];
      foreach ($refClimats as $climat) {
        $climats[] = $climat->getName();
      }

      // On récupère la population
      $population = $planet[0]->field_population->value;

      // On récupère les vaisseaux
      $starships = [];
      $refStarships = $entity->get('field_starships')->referencedEntities();
      foreach ($refStarships as $starship) {
        $starships[] = [
          'name' => $starship->getName(),
          'model' => $starship->field_model->value,
        ];
      }

      /**
       * Mise en forme du tableau qui
       * va être passé au template twig
       */
      $peoples[] = [
        'name' => $entity->getName(),
        'specie' => $specie[0]->getName(),
        'gender' => $entity->field_gender->value,
        'planet' => [
          'name' => $planet[0]->getName(),
          'terrains' => $terrains,
          'climats' => $climats,
          'population' => $population,
        ],
        'starships' => $starships,
      ];

    }

    // Utilisation du thème starwars_listing
    // et du template starwars-listing.html.twig
    return [
      '#theme' => 'starwars_listing',
      '#peoples' => $peoples,
    ];

  }

}
