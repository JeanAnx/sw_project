<?php

/**
 * @file
 * Contains starwars_planet.page.inc.
 *
 * Page callback for Planet entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Planet templates.
 *
 * Default template: starwars_planet.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_starwars_planet(array &$variables) {
  // Fetch Planet Entity Object.
  $starwars_planet = $variables['elements']['#starwars_planet'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
