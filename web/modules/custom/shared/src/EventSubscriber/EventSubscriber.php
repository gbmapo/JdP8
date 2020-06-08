<?php

namespace Drupal\shared\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class EventSubscriber.
 */
class EventSubscriber implements EventSubscriberInterface
{

  protected $currentUser;

  /**
   * Constructs a new EventSubscriber object.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   */
  public function __construct(AccountInterface $current_user)
  {

    $this->currentUser = $current_user;

  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents()
  {

    $events[KernelEvents::RESPONSE][] = ['redirectIf'];
    return $events;

  }

  public function redirectIf(FilterResponseEvent $event)
  {
    $node = $event->getRequest()->attributes->get('node');
    if ($node) {
      if (is_object($node)) {
        if ($node->get('nid')->value == 1) {
          if (!$this->currentUser->isAnonymous()) {
            $path = Url::fromUserInput('/node/25')->toString();
            $event->setResponse(new RedirectResponse($path));
          }
        }
      }
    }
  }

  /**
   * This method is called when the event_subscriber is dispatched.
   *
   * @param \Symfony\Component\EventDispatcher\Event $event
   *   The dispatched event.
   */
  public function eventSubscriber(Event $event)
  {
    \Drupal::messenger()
      ->addMessage('Event event_subscriber thrown by Subscriber in module shared.', 'status', TRUE);
  }

}
