<?php

namespace Drupal\starwars\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\starwars\Entity\People;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\starwars\Service\StarwarsInterface;

/**
 * Class AddPeople.
 */
class AddPeople extends FormBase {

  /**
   * Drupal\starwars\StarwarsInterface definition.
   *
   * @var \Drupal\starwars\StarwarsInterface
   */
  protected $starwarsDefault;
  /**
   * Constructs a new AddPeople object.
   */
  public function __construct(StarwarsInterface $starwars_default) {
    $this->starwarsDefault = $starwars_default;
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
    return 'stawars_add_people';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['id_people'] = [
      '#type' => 'number',
      '#title' => $this->t('ID PEOPLE'),
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
    // Display result.
    $values = $form_state->getValues();

    $id = $values['id_people'];

    $this->starwarsDefault->addPeople($id);
  }

}
