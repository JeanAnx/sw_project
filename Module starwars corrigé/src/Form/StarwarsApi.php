<?php

namespace Drupal\starwars\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class StarwarsApi.
 */
class StarwarsApi extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'starwars.starwarsapi',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'starwars_api';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('starwars.starwarsapi');
    $form['url_api'] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL API'),
      '#description' => $this->t('Entrez l\'url correspondant à l\'api SWAPI sous le format https://monapi.com/api/'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('url_api'),
    ];
    return parent::buildForm($form, $form_state);
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
    parent::submitForm($form, $form_state);

    $this->config('starwars.starwarsapi')
      ->set('url_api', $form_state->getValue('url_api'))
      ->save();
  }

}
