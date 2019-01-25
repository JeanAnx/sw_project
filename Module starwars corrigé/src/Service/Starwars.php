<?php

namespace Drupal\starwars\Service;

use Drupal\Core\Config\ConfigFactory;
use Drupal\starwars\Entity\People;
use Drupal\starwars\Entity\Planet;
use Drupal\starwars\Entity\Starship;
use Drupal\taxonomy\Entity\Term;

/**
 * Class Starwars.
 */
class Starwars implements StarwarsInterface {

  protected $httpClient;

  public function __construct() {
    $this->httpClient = \Drupal::httpClient();
  }

  /**
   *
   * Création d'un personnage et des entités liées (Starship, Planet)
   *
   * @param integer $id Identifiant du personnage ajouté
   *
   */
  public function addPeople($id) {

    /*
     * Requête sur l'API
     * On récupère le personnage au format json
     */
    $request = $this->httpClient->get('https://swapi.co/api/people/' . $id);
    $people = json_decode($request->getBody());

    // On vérifie que le personnage n'existe pas déjà
    $ids = \Drupal::entityQuery('starwars_people')
      ->condition('name', $people->name)
      ->execute();
    // Le personnage n'existe pas alors :
    if (empty($ids)) {

      /**
       * Création/Récupération des entités associées :
       * Planète
       * Starship
       */
      $planet = $this->addPlanet($people->homeworld);
      $specie = $this->getSpecies($people->species[0]);


      $peopleStarships = [];
      // On parcours la liste des vaisseaux
      $starships = $people->starships;
      foreach ($starships as $starship) {
        $peopleStarships[] = $this->addStarship($starship);
      }
      /**
       * On vérifie que l'espèce n'existe pas déjà
       */
      $specieTerm = \Drupal::entityQuery('taxonomy_term')
        ->condition('name', $specie)
        ->execute();

      // current permet de récupérer le premier élement du tableau
      $specieTerm = current($specieTerm);

      if (empty($specieTerm)) {
        $term = Term::create(
          [
            'vid' => 'species',
            'name' => $specie,
          ]
        );

        $term->save();
        $specieTerm = $term->id();

        drupal_set_message("L'espèce " . $specie . " a été ajoutée");
      }

      /**
       * Création de notre entité People
       */
      $newPeople = People::create([
        'name' => $people->name,
        'field_gender' => $people->gender,
        'field_homeworld' => $planet, // Identifiant de la planète
        'field_species' => $specieTerm, // Identifiant ou tableau d'ids [id1,id2,id3] du terme Species
        'field_starships' => $peopleStarships // Identifiants des vaisseaux

      ]);
      $newPeople->save();

      drupal_set_message('Le personnage ' . $people->name . ' a été ajouté');
    }
    else {
      drupal_set_message('Le personnage ' . $people->name . ' existe déjà');
    }


  }

  /**
   *
   * Création de l'entité Planet et des termes associés
   *
   * @param string $uri URI de l'api
   *
   * @return integer Identifiant de la planète
   */
  public function addPlanet($uri) {


    /*
    * Requête sur l'API
    * On récupère la planète au format json
    */
    $request = $this->httpClient->get($uri);
    $planet = json_decode($request->getBody());


    /**
     * On vérifie que la planète n'existe pas déjà
     * Si c'est le cas on récupère l'identifiant pour l'ajouter au personnage
     */
    $result = \Drupal::entityQuery('starwars_planet')
      ->condition('name', $planet->name)
      ->execute();

    // La planète n'existe pas
    if (empty($result)) {

      //Transforme la liste des terrains sous la forme terrain1,terrain2,terrain3 en tableau
      $terrains = explode(", ", $planet->terrain);
      //Transforme la liste des climats sous la forme climat1,climat2,climat3 en tableau
      $climats = explode(", ", $planet->climate);

      /**
       * Création des terrains
       * On parcours le tableau de terme afin de vérifier
       * si ceux-ci n'existent pas sinon on récupère l'id des termes déjà existant
       */
      $termsTerrains = [];
      foreach ($terrains as $terrain) {

        $terrainTerm = \Drupal::entityQuery('taxonomy_term')
          ->condition('name', $terrain)
          ->execute();

        if (empty($terrainTerm)) {
          // Création d'un terme Terrain
          $term = Term::create(
            [
              'vid' => 'terrain',
              'name' => $terrain,
            ]
          );

          $term->save();
          $termsTerrains[] = $term->id();

          drupal_set_message('Le terrain ' . $terrain . ' a été ajouté');
        }
        else {
          $termsTerrains[] = current($terrainTerm);
        }

      }

      /**
       * Création des climats
       * On parcours le tableau de terme afin de vérifier
       * si ceux-ci n'existent pas sinon on récupère l'id des termes déjà existant
       */
      $termsClimats = [];
      foreach ($climats as $climat) {

        $climatTerme = \Drupal::entityQuery('taxonomy_term')
          ->condition('name', $climat)
          ->execute();

        if (empty($climatTerme)) {
          // Création d'un terme Terrain
          $term = Term::create(
            [
              'vid' => 'climat',
              'name' => $climat,
            ]
          );

          $term->save();
          $termsClimats[] = $term->id();

          drupal_set_message('Le climat ' . $climat . ' a été ajouté');
        }
        else {
          $termsClimats[] = current($terrainTerm);
        }

      }

      // Création de l'entité Planet
      $newPlanet = Planet::create([
        'name' => $planet->name,
        'field_terrain' => $termsTerrains,
        'field_climat' => $termsClimats,
        'field_population' => $planet->population,
      ]);

      $newPlanet->save();

      drupal_set_message('La planète ' . $planet->name . ' a été ajoutée');

      return $newPlanet->id(); // Renvoie l'identifiant de la planète ajouté
    }
    else {
      return current($result); // Renvoie l'identifiant de la planète déjà existante
    }

  }


  /**
   * Récupère le nom de l'espèce via l'URL de l'API
   *
   * @param string $uri URL de l'API
   *
   * @return string Nom de l'espèce
   */
  public function getSpecies($uri) {
    $request = $this->httpClient->get($uri);
    $specie = json_decode($request->getBody());
    return $specie->name;
  }

  /**
   * Création de l'entité Starship via l'URL de l'API
   *
   * @param string $uri URL de l'API
   *
   * @return integer $id Identifiant de l'API
   */
  public function addStarship($uri) {
    /*
    * Requête sur l'API
    * On récupère le vaisseau au format json
    */
    $request = $this->httpClient->get($uri);
    $starship = json_decode($request->getBody());


    /**
     * On vérifie que la planète n'existe pas déjà
     * Si c'est le cas on récupère l'identifiant pour l'ajouter au personnage
     */
    $result = \Drupal::entityQuery('starwars_starship')
      ->condition('name', $starship->name)
      ->execute();

    /**
     * On vérifie que la planète n'existe pas déjà
     * Si c'est le cas on récupère l'identifiant pour l'ajouter au personnage
     */
    // La planète n'existe pas
    if (empty($result)) {

      // Création de l'entité Planet
      $newStarship = Starship::create([
        'name' => $starship->name,
        'field_model' => $starship->model,
      ]);

      $newStarship->save();

      drupal_set_message('Le vaisseau ' . $starship->name . ' a été ajouté');

      return $newStarship->id(); // Renvoie l'identifiant du vaisseau ajouté
    }
    else {
      return current($result); // Renvoie l'identifiant du vaisseau déjà existant
    }
  }

}
