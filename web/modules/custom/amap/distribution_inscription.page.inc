<?php

/**
 * @file
 * Contains distribution_inscription.page.inc.
 *
 * Page callback for Distribution inscription entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Distribution inscription templates.
 *
 * Default template: distribution_inscription.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_distribution_inscription(array &$variables)
{
  // Fetch DistributionInscription Entity Object.
  $distribution_inscription = $variables['elements']['#distribution_inscription'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
