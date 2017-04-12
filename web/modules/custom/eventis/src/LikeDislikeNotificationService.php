<?php

namespace Drupal\eventis;

/**
 * Created by PhpStorm.
 * User: Zani
 * Date: 11.04.2017
 * Time: 16:46
 */
class LikeDislikeNotificationService {

    public $form;
    public $form_state;

    public function setForm($form){
      $this->form = $form;
    }

    public function setFormState($form_state){
      $this->form = $form_state;
}


    public function renderButton(){
     return [
        '#type' => 'checkbox',
        '#title' => t('Show Like\Dislike notifications'),
       // '#value' => 0,
      ];
    }
}