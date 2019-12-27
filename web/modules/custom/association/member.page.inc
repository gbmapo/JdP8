<?php

/**
 * @file
 * Contains member.page.inc.
 *
 * Page callback for Member entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Member templates.
 *
 * Default template: member.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_member(array &$variables)
{
  // Fetch Member Entity Object.
  $member = $variables['elements']['#member'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
