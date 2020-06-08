<?php

namespace Drupal\shared\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class RouteSubscriber.
 *
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase
{

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection)
  {
    switch (true) {
      case $route = $collection->get('forum.index'):
      case $route = $collection->get('forum.page'):
        $route->setRequirement('_permission', 'create forum content');
        break;
      default:
    }

  }

}
