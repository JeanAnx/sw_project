<?php

/**
 * @file
 * Contains people_entity.page.inc.
 *
 * Page callback for People entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for People entity templates.
 *
 * Default template: people_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_people_entity(array &$variables) {
  // Fetch PeopleEntity Entity Object.
  $people_entity = $variables['elements']['#people_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
