<?php

/**
 * @file
 * Contains contract_subscription.page.inc.
 *
 * Page callback for Contract subscription entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Contract subscription templates.
 *
 * Default template: contract_subscription.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_contract_subscription(array &$variables)
{
  // Fetch ContractSubscription Entity Object.
  $contract_subscription = $variables['elements']['#contract_subscription'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
