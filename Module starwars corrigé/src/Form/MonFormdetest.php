<?php

namespace Drupal\starwars\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\starwars\Service\StarwarsInterface;

/**
 * Class MonFormdetest.
 */
class MonFormdetest extends FormBase {

  /**
   * Drupal\starwars\Service\StarwarsInterface definition.
   *
   * @var \Drupal\starwars\Service\StarwarsInterface
   */
  protected $starwarsService;
  /**
   * Constructs a new MonFormdetest object.
   */
  public function __construct(
    StarwarsInterface $starwars_service
  ) {
    $this->starwarsService = $starwars_service;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('starwars.service')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mon_formdetest';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
      drupal_set_message($key . ': ' . $value);
    }

  }

}
