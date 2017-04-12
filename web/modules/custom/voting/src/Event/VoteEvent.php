<?php
/**
 * Created by PhpStorm.
 * User: zann
 * Date: 4/10/2017
 * Time: 10:55 PM
 */

namespace Drupal\voting\Event;

use Symfony\Component\EventDispatcher\Event;


class VoteEvent extends Event {

    const NEW_VOTE = 'voting.new_vote';

    protected $user;
    protected $title;
    protected $vote_type;
    protected $entity_type;

    public function __construct($user, $title, $vote_type, $entity_type ){

        $this->user = $user;
        $this->title = $title;
        $this->vote_type = $vote_type;
        $this->entity_type = $entity_type;


    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getVoteType()
    {
        return $this->vote_type;
    }

    /**
     * @return mixed
     */
    public function getEntityType()
    {
        return $this->entity_type;
    }
}