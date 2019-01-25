<?php

/**
 * @file
 * Contains starwars_people.page.inc.
 *
 * Page callback for People entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for People templates.
 *
 * Default template: starwars_people.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_starwars_people(array &$variables) {
  // Fetch People Entity Object.
  $starwars_people = $variables['elements']['#starwars_people'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
