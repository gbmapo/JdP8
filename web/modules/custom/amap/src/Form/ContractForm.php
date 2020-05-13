<?php

namespace Drupal\amap\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Contract edit forms.
 *
 * @ingroup amap
 */
class ContractForm extends ContentEntityForm
{

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    /* @var $entity \Drupal\amap\Entity\Contract */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    $form['type']['widget']['#sort_options']  = TRUE;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state)
  {
    $entity = $this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        \Drupal::messenger()->addMessage($this->t('Contract « %label » has been added.', array(
          '%label' => $entity->label()
        )));
        break;

      default:
        \Drupal::messenger()->addMessage($this->t('Contract « %label » has been updated.', array(
          '%label' => $entity->label()
        )));
    }

    $form_state->setRedirect('view.amap_contracts.page_1');
  }

}
