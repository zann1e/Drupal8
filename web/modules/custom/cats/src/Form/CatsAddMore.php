<?php

namespace Drupal\cats\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Implements the ajax demo form controller.
 *
 * This example demonstrates using ajax callbacks to populate the options of a
 * color select element dynamically based on the value selected in another
 * select element in the form.
 *
 * @see \Drupal\Core\Form\FormBase
 * @see \Drupal\Core\Form\ConfigFormBase
 */
class CatsAddMore extends FormBase {


  public function buildForm(array $form, FormStateInterface $form_state) {

    $client = \Drupal::httpClient();

    $request = $client->get('http://thecatapi.com/api/images/get?format=xml&results_per_page=5&size=small');
    $response = $request->getBody();
    $crawler = new Crawler();
    $crawler->addContent($response);

    $data = $crawler->filter('url')->extract(['_text']);


    $form['description'] = array(
      '#markup' => '<div>' . t('Load more cats from API.') . '</div>',
    );


    $invasion_message = [
      'Jesus Cries! More Cats ^.^',
      'Cats INVASION!',
      'We Come Here to Chew BubbleGum and Kick Your Ass',
      'KITTY KAT!',
      'Are you tired of CATS?',
      'STOP THEM!',
      'I\'m-paws-sible',
      'A CAT-HAS-TROPHY!',
      'The purrpatrators are here!',
      'One more Cat-alogue',

    ];


    $form['cats'] = [
      '#type' => 'fieldset',
      '#title' => $invasion_message[rand(0, 9)],
      '#prefix' => '<div id="cats-api-wrapper">',
      '#suffix' => '</div>',
    ];


    // Insert 5 images <img> into array
    $i = 0;
    foreach ($data as $image) {
      $name = 'image' . $i++;
      $form['cats']['images'][$name] = ['#markup' => '<img src="' . $image . '"><br>'];
    }

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['addmore']['actions']['add_cat'] = [
      '#type' => 'submit',
      '#value' => t('Add 5 more'),
      '#submit' => array('::addOne'),
      '#ajax' => [
        'callback' => '::addmoreCallback',
        'wrapper' => 'cats-api-wrapper',
        'method' => 'append',
      ],
    ];

    $form_state->setCached(FALSE);


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'api_cats_form';
  }

  // Callback to add 5 more new cats
  public function addmoreCallback(array &$form) {

    return $form['cats'];
  }

  // Rebuild new 5 cats
  public function addOne(array &$form, FormStateInterface $form_state) {

    $form_state->setRebuild();
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
