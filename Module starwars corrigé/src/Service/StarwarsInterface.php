<?php

namespace Drupal\starwars\Service;

/**
 * Interface StarwarsInterface.
 */
interface StarwarsInterface {


  /**
   *
   * Création d'un personnage et des entités liées (Starship, Planet)
   *
   * @param integer $id Identifiant du personnage ajouté
   *
   */
  public function addPeople($id);

  /**
   *
   * Création de l'entité Planet et des termes associés
   *
   * @param string $uri URI de l'api
   *
   * @return integer Identifiant de la planète
   */
  public function addPlanet($uri);


  /**
   * Récupère le nom de l'espèce via l'URL de l'API
   *
   * @param string $uri URL de l'API
   *
   * @return string Nom de l'espèce
   */
  public function getSpecies($uri);


  /**
   * Création de l'entité Starship via l'URL de l'API
   *
   * @param string $uri URL de l'API
   *
   * @return integer $id Identifiant de l'API
   */
  public function addStarship($uri);

}
