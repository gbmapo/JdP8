<?php

namespace Drupal\sel\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Service category edit forms.
 *
 * @ingroup sel
 */
class ServiceCategoryForm extends ContentEntityForm
{

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    /* @var $entity \Drupal\sel\Entity\ServiceCategory */
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
        \Drupal::messenger()->addMessage($this->t('Service category « %label » has been added.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        \Drupal::messenger()->addMessage($this->t('Service category « %label » has been updated.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.service_category.canonical', ['service_category' => $entity->id()]);
  }

}
