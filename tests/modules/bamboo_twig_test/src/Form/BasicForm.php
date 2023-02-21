<?php

namespace Drupal\bamboo_twig_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * A Basic Form without parameters.
 *
 * @internal
 */
class BasicForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bamboo_twig_test_basic_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['checkbox'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Checkbox'),
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save configuration'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
