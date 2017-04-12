<?php
/**
 * Created by PhpStorm.
 * User: Zani
 * Date: 06.04.2017
 * Time: 16:14
 */

namespace Drupal\voting\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\voting\Event\VoteEvent;


class ZannAjaxFormLike extends FormBase {

  protected $uid;
  protected $nid;
  protected $votingService;
  protected $title;

  public function __construct() {

    $this->uid = \Drupal::currentUser()->id();
    $node = \Drupal::routeMatch()->getParameter('node');
    $this->title = $node->getTitle();
    $this->nid = $node->nid->value;

    $this->votingService = \Drupal::service('zann.voting');

  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'zann_ajax_voting_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {


    $vote = $this->votingService->getLike($this->nid, $this->uid);
    $total_votes = $this->votingService->totalLikesByNid($this->nid);


    if ($vote['vote'] == 1) {
      $field_like_disabled = TRUE;
      $field_dislike_disabled = FALSE;
    }

    if ($vote['vote'] == -1) {
      $field_dislike_disabled = TRUE;
      $field_like_disabled = FALSE;
    }


    $form = array(
      '#prefix' => '<div id="zann-wrapper">',
      '#suffix' => '</div>'
    );


    $form_state->setCached(FALSE);
    $form['action'] = [
      '#type' => 'actions',
    ];

    $form['action']['like'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Like'),
      '#button_type' => 'primary',
      '#submit' => ['::submitLike'],
      '#disabled' => $field_like_disabled,
      '#ajax' => [
        'callback' => '::callbackAjaxRefresh',
        'event' => 'click',
        'wrapper' => 'zann-wrapper',
        'effect' => 'fade',
      ],
    );

    $form['action']['dislike'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Dislike'),
      '#button_type' => 'primary',
      '#submit' => ['::submitDislike'],
      '#disabled' => $field_dislike_disabled,
      '#ajax' => [
        'callback' => '::callbackAjaxRefresh',
        'event' => 'click',
        'wrapper' => 'zann-wrapper',
        'effect' => 'fade',
      ],
    );

    $form['total'] = [
      '#type' => 'markup',
      '#markup' => '<span>Total Karma: ' . $total_votes['total'] . '</span>',
    ];


    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Validate Nothing
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // To do nothing

  }

  // Callback for Like action
  public function callbackAjaxRefresh(array &$form, FormStateInterface $form_state) {

    return $form;
  }

  public function submitLike(array &$form, FormStateInterface $form_state) {

    $uid = $this->uid;
    $nid = $this->nid;

    $this->votingService->setLike($nid, $uid);

    $form_state->setRebuild(TRUE);

    $title = $this->title;
    $user_name = \Drupal::currentUser()->getAccountName();
    $dispatcher = \Drupal::service('event_dispatcher');
    $event = new VoteEvent($user_name, $title, 'liked', 'book');
    $dispatcher->dispatch(VoteEvent::NEW_VOTE, $event);

    \Drupal::moduleHandler()->invokeAll('vote_liked', [$event]);
  }

  public function submitDislike(array &$form, FormStateInterface $form_state) {

    $uid = $this->uid;
    $nid = $this->nid;

    $this->votingService->setDislike($nid, $uid);

    $form_state->setRebuild(TRUE);

    $title = $this->title;
    $user_name = \Drupal::currentUser()->getAccountName();
    $dispatcher = \Drupal::service('event_dispatcher');
    $event = new VoteEvent($user_name, $title, 'disliked', 'book');
    $dispatcher->dispatch(VoteEvent::NEW_VOTE, $event);

    \Drupal::moduleHandler()->invokeAll('vote_disliked', [$event]);
  }
}