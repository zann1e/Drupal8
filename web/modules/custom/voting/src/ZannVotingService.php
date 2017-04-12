<?php
/**
 * Created by PhpStorm.
 * User: Zani
 * Date: 05.04.2017
 * Time: 17:01
 */

namespace Drupal\voting;

use Drupal\Core\Database\Connection;

class ZannVotingService {

  private $database;

  public function __construct(Connection $database) {
     $this->database = $database;
  }

  public function getLike($nid, $uid){

    $query = $this->database->select('voting', 'bl');
    $query->fields('bl', ['uid', 'nid', 'vote']);
    $query->condition('bl.uid', $uid);
    $query->condition('bl.nid', $nid);
    $query->range(0, 1);
    $result = $query->execute()->fetchAssoc();
    return $result;
  }

  public function setLike($nid, $uid){
      $query = $this->database->merge('voting');
      $query->key(['uid' => $uid, 'nid' => $nid]);
      $query->insertFields([
            'uid' => $uid,
            'nid' => $nid,
            'vote' => 1,
      ]);
      $query->updateFields([
        'vote' => 1,
      ]);
      $query->execute();

  }

  public function setDislike($nid, $uid){
    $query = $this->database->merge('voting');
    $query->key(['uid' => $uid, 'nid' => $nid]);
    $query->insertFields([
      'uid' => $uid,
      'nid' => $nid,
      'vote' => -1,
    ]);
    $query->updateFields([
      'vote' => -1,
    ]);
    $query->execute();

  }

  public function totalLikesByNid($nid){
    $query = $this->database->select('voting');
    $query->condition('nid', $nid);
    $query->addExpression('sum(vote)', 'total');
    $result = $query->execute()->fetchAssoc();
    return $result;
  }

}