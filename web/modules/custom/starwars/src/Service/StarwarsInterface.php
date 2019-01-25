<?php

namespace Drupal\starwars\Service;

interface StarwarsInterface
{
    /**
     * Add a StarWars Character
     *
     * @param integer $id The StarWars People Id
     *
     * @return mixed
     */
    public function addPeople($id);

    /**
     * Add a StarWars Planet
     *
     * @param string $uri The Swapi URI
     *
     * @return string $id An Entity Planet Id
     */
    public function addPlanet($uri);
    /**
     * Get a Species name from the Swapi URI
     *
     * @param string $uri The Swapi URI
     *
     * @return string The species name
     */
    public function getSpecies($uri);
    /**
     * Add a StarWars StarShip
     *
     * @param string $uri The Swapi URI
     *
     * @return string $id An Entity Starship Id
     */
    public function addStarship($uri);

}