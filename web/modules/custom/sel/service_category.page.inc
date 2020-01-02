<?php

/**
 * @file
 * Contains service_category.page.inc.
 *
 * Page callback for Service category entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Service category templates.
 *
 * Default template: service_category.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_service_category(array &$variables) {
  // Fetch ServiceCategory Entity Object.
  $service_category = $variables['elements']['#service_category'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
