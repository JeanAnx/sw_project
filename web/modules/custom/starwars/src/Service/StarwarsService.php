<?php

namespace Drupal\starwars\Service;

use Drupal\starwars\Entity\People;
use Drupal\starwars\Entity\PeopleEntity;
use Drupal\starwars\Entity\Planet;
use Drupal\Core\Config\ConfigFactory;
use Drupal\starwars\Entity\Starship;
use Drupal\taxonomy\Entity\Term;


class StarwarsService implements StarwarsInterface
{

    protected $httpClient;

    /**
     * Constructs a new Starwars object.
     */
    public function __construct()
    {
        $this->httpClient = \Drupal::httpClient();

    }

    /**
     * Add a StarWars Character
     *
     * @param integer $id The StarWars People Id
     *
     * @return mixed
     */

    public function addPeople($id)
    {

        /**
         * Requête sur l'API
         * On récupère le personnage au format Json
         */

        $request = $this->httpClient->get('https://swapi.co/api/people/' . $id);
        $people = json_decode($request->getBody());

        // On vérifie que le personnage n'existe pas déjà
        $ids = \Drupal::entityQuery(('people_entity'))
            ->condition('name', $people->name)
            ->execute();

        // Le personnage n'existe pas alors ( et donc si 'ids' est vide) :
        if (empty($ids)) {

            /**
             * Création/Récupération des entités associées :
             * Planète
             * Starship
             */

            $planet = $this->addPlanet($people->homeworld);
            $species = $this->getSpecies($people->species);


            foreach ($people->starships as $theStarship) {
                $this->addStarship($theStarship);
            }

            $listStarships = implode(',' , $people->starships);

            $newPeople = PeopleEntity::create([
                'name' => $people->name,
                'field_homeworld' => $people->homeworld,
                'field_espece' => $people->species,
                'field_gender' => $people->gender,
                'field_starships' => $people->starships,

            ]);

            $newPeople->save();

            drupal_set_message('Le personnage ' . $people->name . ' a été créé');

        } else {

            drupal_set_message('Le personnage ' . $people->name . ' a déjà été créé !');


        }

    }

    /**
     * Add a StarWars Planet
     *
     * @param string $uri The Swapi URI
     *
     * @return string $id An Entity Planet Id
     */
    public function addPlanet($uri)
    {

        $request = $this->httpClient->get($uri);
        $planet = json_decode($request->getBody());

        // On vérifie que la planète n'existe pas déjà
        $existingPlanet = \Drupal::entityQuery(('starwars_planet'))
            ->condition('name', $planet->name)
            ->execute();

        if (empty($existingPlanet)) {

            // Création de l'entité Planète

            $newPlanete = Planet::create([
                'name' => $planet->name,
                'field_climat' => $planet->model,
                'field_population' => $planet->population,
                'field_terrain' => $planet->terrain,
            ]);

            $newPlanete->save();

            drupal_set_message('La planète ' . $newPlanete->getName() . ' a été créée');

            return $newPlanete->id();

        } else {

            drupal_set_message('La planète ' . $existingPlanet->name . ' a déjà été créée');

            return current($existingPlanet);
        }

    }

    /**
     * Get a Species name from the Swapi URI
     *
     * @param string $uri The Swapi URI
     *
     * @return string The species name
     */
    public function getSpecies($uri)
    {
/*
        $request = $this->httpClient->get($uri);
        $species = json_decode($request->getBody());

        $existingSpecies = \Drupal::entityQuery(('field_tags'))
            ->condition('tag', $species->name)
            ->execute();

        if (empty($existingSpecies)) {

            $newSpecies = Node::create([
                'name' => $starship->name,
                'field_model' => $starship->model
            ]);

            $newStarship->save();

            drupal_set_message('Le vaissean ' . $starship->name . ' a été créé');

            return $newStarship->id();

        } else {
            return current($existingStarships);
        }
*/
    }

    /**
     * Add a StarWars StarShip
     *
     * @param string $uri The Swapi URI
     *
     * @return string $id An Entity Starship Id
     */
    public function addStarship($uri)
    {

        $request = $this->httpClient->get($uri);
        $starship = json_decode($request->getBody());

        // On vérifie que le starship n'existe pas déjà
        $existingStarships = \Drupal::entityQuery(('starship'))
            ->condition('name', $starship->name)
            ->execute();

        if (empty($existingStarships)) {

            // Création de l'entité Starship

            $newStarship = Starship::create([
                'name' => $starship->name,
                'field_model' => $starship->model
            ]);

            $newStarship->save();

            drupal_set_message('Le vaisseau ' . $newStarship->name . ' a été créé');

            return $newStarship->id();

        } else {
            return current($existingStarships);
        }

    }

}