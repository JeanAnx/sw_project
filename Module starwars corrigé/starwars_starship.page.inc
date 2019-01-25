<?php

/**
 * @file
 * Contains starwars_starship.page.inc.
 *
 * Page callback for Starship entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Starship templates.
 *
 * Default template: starwars_starship.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_starwars_starship(array &$variables) {
  // Fetch Starship Entity Object.
  $starwars_starship = $variables['elements']['#starwars_starship'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
