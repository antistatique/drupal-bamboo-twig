<?php

namespace Drupal\bamboo_twig_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * A Form with any mixed parameter.
 *
 * @internal
 */
class MixedParamForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bamboo_twig_test_mixed_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $arg = NULL) {
    $form['text'] = [
      '#type' => 'textfield',
      '#default_value' => $arg,
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
