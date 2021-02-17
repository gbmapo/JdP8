<?php

namespace Drupal\amap\Form;

use Drupal\Core\Form\ConfirmFormBase;
use \Drupal\Core\Form\FormStateInterface;
use \Drupal\Core\Url;

/**
 * Class ContractDuplicateForm.
 */
class ContractDuplicateForm extends ConfirmFormBase
{

  protected $id;
  protected $name;

  public function getFormId()
  {
    return 'contract_duplicate_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL)
  {
    $this->id = $id;
    $oContract = \Drupal::entityTypeManager()
      ->getStorage('contract')
      ->load($id);
    $this->name = $oContract->get('name')->getString();
    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $co_id = $this->id;

    $database = \Drupal::database();

    $transaction = $database->startTransaction();

    $query = $database->select('contract', 'co')
      ->fields('co')
      ->condition('co.id', $co_id);
    $contract = $query->execute()->fetchAssoc();
    if ($contract) {

      $contract['id'] = null;
      $contractName = $contract['name'];
      $contract['name'] .= ' Copie';
      $contract['isvisible'] = '0';
      $contract['isopenforsubscription'] = '0';
      $contract['owner_id'] = $this->currentUser()->id();
      $contract['created'] = time();
      $contract['changed'] = time();

      try {

        $newId = $database
          ->insert('contract')
          ->fields($contract)
          ->execute();

        $query = $database->select('contract_subscription', 'cs')
          ->fields('cs')
          ->condition('contract_id', $co_id);
        $subscriptions = $query->execute()->fetchAllAssoc('id');
        foreach ($subscriptions as $subscription) {
          $subscription->id = null;
          $subscription->contract_id = $newId;
          $subscription->owner_id = $this->currentUser()->id();
          $subscription->created = time();
          $subscription->changed = time();
          $aSubscription = (array)$subscription;
          $database
            ->insert('contract_subscription')
            ->fields($aSubscription)
            ->execute();
        }

        \Drupal::messenger()->addMessage($this->t('Contract « %name » has been duplicated.', ['%name' => $contractName]));

      } catch (\Exception $e) {
        $transaction->rollBack();
        watchdog_exception('amap', $e);
      }
    }

    $form_state->setRedirect('amap.contracts');

  }

  public function getCancelUrl()
  {
    return new Url('amap.contracts');
  }

  public function getQuestion()
  {
    return $this->t('Are you sure you want to duplicate contract « %name »?', [
      '%name' => $this->name,
    ]);
  }

}
