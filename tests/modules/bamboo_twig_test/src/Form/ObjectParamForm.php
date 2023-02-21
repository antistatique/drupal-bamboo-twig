<?php

namespace Drupal\bamboo_twig_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * A Form with an object parameter.
 *
 * @internal
 */
class ObjectParamForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bamboo_twig_test_object_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, object $args = NULL) {
    $form['text'] = [
      '#type' => 'textfield',
      '#default_value' => $args->text,
      '#title' => $this->t('Text'),
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
