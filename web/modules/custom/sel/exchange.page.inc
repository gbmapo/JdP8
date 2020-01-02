<?php

/**
 * @file
 * Contains exchange.page.inc.
 *
 * Page callback for Exchange entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Exchange templates.
 *
 * Default template: exchange.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_exchange(array &$variables) {
  // Fetch Exchange Entity Object.
  $exchange = $variables['elements']['#exchange'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
