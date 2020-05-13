<?php

namespace Drupal\association\Form;

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
    /* @var $entity \Drupal\association\Entity\Member */
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

    if ($entity->status->value == 0) {
      // List all Persons for this Member
      $id       = $entity->id->value;
      $database = \Drupal::database();
      $query    = $database->select('person', 'ap');
      $query->fields('ap', ['id', 'member_id'])
        ->condition('member_id', $id, '=');
      $results = $query->execute();
      // Make all these Persons Inactive
      $storage = \Drupal::entityTypeManager()->getStorage('person');
      foreach ($results as $key => $result) {
        $person = $storage->load($result->id);
        if ($person->iscontact->value) {
          $person->set("iscontact", 0);
        }
        $person->set("isactive", 0);
        $person->set("member_id", null);
        $person->save();
      }
    }

    $status = parent::save($form, $form_state);
    switch ($status) {
      case SAVED_NEW:
        \Drupal::messenger()->addMessage($this->t('Member « %label » has been added.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        \Drupal::messenger()->addMessage($this->t('Member « %label » has been updated.', [
          '%label' => $entity->label(),
        ]));
    }

    $form_state->setRedirect('view.association_members.page_1');

  }

}





