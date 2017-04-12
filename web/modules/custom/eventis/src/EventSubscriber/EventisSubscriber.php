<?php

namespace Drupal\eventis\EventSubscriber;

use Drupal\voting\Event\VoteEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Created by PhpStorm.
 * User: zann
 * Date: 4/10/2017
 * Time: 11:23 PM
 */
class EventisSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    $events[VoteEvent::NEW_VOTE][] = array('onNewVote', 800);
    return $events;
  }

  public function onNewVote(VoteEvent $event) {

    $uid = \Drupal::currentUser()->id();
    $value = \Drupal::service('user.data')
      ->get('eventis', $uid, 'show_like_dislike');

    if ($value == 1) {
      drupal_set_message("Via 'EventisSubscriber': " . $event->getUser() . " " . $event->getVoteType() . " " . $event->getTitle());
    }

  }
}