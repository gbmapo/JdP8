<?php

namespace Drupal\amap\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Contract subscription edit forms.
 *
 * @ingroup amap
 */
class ContractSubscriptionForm extends ContentEntityForm
{

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    /* @var $entity \Drupal\amap\Entity\ContractSubscription */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

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
        \Drupal::messenger()->addMessage($this->t('Contract subscription« %label » has been added.', array(
          '%label' => $entity->label()
        )));
        break;

      default:
        \Drupal::messenger()->addMessage($this->t('Contract subscription« %label » has been updated.', array(
          '%label' => $entity->label()
        )));
    }
    $form_state->setRedirect('entity.contract_subscription.canonical', ['contract_subscription' => $entity->id()]);
  }

}
