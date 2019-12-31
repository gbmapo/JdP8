<?php

/**
 * @file
 * Contains contract_type.page.inc.
 *
 * Page callback for Contract type entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Contract type templates.
 *
 * Default template: contract_type.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_contract_type(array &$variables)
{
  // Fetch ContractType Entity Object.
  $contract_type = $variables['elements']['#contract_type'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
