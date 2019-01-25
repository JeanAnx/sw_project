<?php

namespace Drupal\starwars\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\starwars\Service\StarwarsInterface;

/**
 * Class PeopleForm.
 */
class PeopleForm extends FormBase {

  /**
   * Drupal\starwars\Service\StarwarsInterface definition.
   *
   * @var \Drupal\starwars\Service\StarwarsInterface
   */
  protected $StarwarsService;
  /**
   * Constructs a new PeopleForm object.
   */
  public function __construct(
    StarwarsInterface $starwars_starwars_service
  ) {
    $this->StarwarsService = $starwars_starwars_service;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('starwars.starwars_service')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'people_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['id_people'] = [
      '#type' => 'number',
      '#title' => $this->t('Id People'),
      '#description' => $this->t('L&#039;id du personnage'),
      '#weight' => '0',
    ];
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
      $values = $form_state->getValues();
      $id = $values['id_people'];
      $this->StarwarsService->addPeople($id);

  }

}
