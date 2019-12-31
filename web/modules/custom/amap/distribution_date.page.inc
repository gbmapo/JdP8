<?php

/**
 * @file
 * Contains distribution_date.page.inc.
 *
 * Page callback for Distribution date entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Distribution date templates.
 *
 * Default template: distribution_date.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_distribution_date(array &$variables)
{
  // Fetch DistributionDate Entity Object.
  $distribution_date = $variables['elements']['#distribution_date'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
