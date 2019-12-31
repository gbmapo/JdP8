<?php

namespace Drupal\amap\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Distribution date edit forms.
 *
 * @ingroup amap
 */
class DistributionDateForm extends ContentEntityForm
{

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    /* @var $entity \Drupal\amap\Entity\DistributionDate */
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
    $entity->numberofproducts->value = 0;
    foreach ($entity as $key => $value) {
      if (substr($key, 0, 7) == 'product') {
        $entity->numberofproducts->value += ($entity->$key->value) ? 1 : 0;
      }
    }

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        \Drupal::messenger()->addMessage($this->t('Distribution date « %label » has been added.', array(
          '%label' => $entity->label()
        )));
        break;

      default:
        \Drupal::messenger()->addMessage($this->t('Distribution date « %label » has been updated.', array(
          '%label' => $entity->label()
        )));
    }

    $form_state->setRedirect('entity.distribution_date.canonical', ['distribution_date' => $entity->id()]);
  }

}
