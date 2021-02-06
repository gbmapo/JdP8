<?php

namespace Drupal\association\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class MemberSettingsForm.
 *
 * @ingroup association
 */
class MemberSettingsForm extends FormBase
{

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId()
  {
    return 'member_settings';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    // Empty implementation of the abstract submit class.
  }

  /**
   * Defines the settings form for Member entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['member_settings']['#markup'] = 'Settings form for Member entities. Manage field settings here.';
    return $form;
  }

}
