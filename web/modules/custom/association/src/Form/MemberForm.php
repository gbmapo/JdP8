<?php

namespace Drupal\association\Form;

use Drupal;
use Drupal\association\Entity\Member;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Member edit forms.
 *
 * @ingroup association
 */
class MemberForm extends ContentEntityForm
{

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    /* @var $entity Member */
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
        Drupal::messenger()->addMessage($this->t('Member « %label » has been added.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        Drupal::messenger()->addMessage($this->t('Member « %label » has been updated.', [
          '%label' => $entity->label(),
        ]));
    }

    $form_state->setRedirect('view.association_members.page_1');

  }

}





