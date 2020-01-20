<?php

namespace Drupal\amap\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\CloseModalDialogCommand;


/**
 * Class ContractSubscriptionTableForm.
 */
class ContractSubscriptionTableForm extends FormBase
{


  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'contract_subscription_table_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $contract = NULL)
  {

    $oContract = \Drupal::entityTypeManager()->getStorage('contract')->load($contract);
    $sContractIsVisible = $oContract->get('isvisible')->getString();
    $sContractType = $oContract->get('type')->getString();

    $aContractType = _detail_contract_type($sContractType);
    $iNumberOfQuantities = $aContractType[1];
    $aContractTypeHeader = $aContractType[2];

    $form['subscriptions'] = array(
      '#type' => 'table',
      '#sticky' => TRUE,
      '#responsive' => TRUE,
      '#id' => 'subscriptions',
      '#quantities' => $iNumberOfQuantities,
    );

    $form['subscriptions']['#header'] = array('member' => t('Member'),);
    $form['subscriptions']['#header'] = array_merge($form['subscriptions']['#header'], $aContractTypeHeader);
    $aContractStandardHeader = array(
      'sharedwith' => t('Shared with'),
      'comment' => t('Comment'),
      'file' => t('•••'),
    );
    $form['subscriptions']['#header'] = array_merge($form['subscriptions']['#header'], $aContractStandardHeader);

//  Liste des Adhérents pour 'Partagé avec'
    $query_am = \Drupal::database()->select('member', 'am');
    $query_am->fields('am', ['id', 'designation']);
    $query_am->condition('status', 2, '>=')
      ->orderBy('designation', 'ASC');
    $results_am = $query_am->execute()->fetchAllKeyed();
    $results_am = array("0" => "") + $results_am;

//  Liste des Adhérents avec leur souscription éventuelle
    $sTemp1 = "";
    $sTemp2 = "";
    for ($i = 1; $i <= $iNumberOfQuantities; $i++) {
      $sTemp1 .= 'quantity' . sprintf("%02d", $i) . ',';
      $sTemp2 .= "'',";
    }
    $query = "
      SELECT
          am.id as am_id,
          designation,
          sharedwith_member_id,
          cs.comment,
          cs.file__target_id,"
      . $sTemp1 .
      "    cs.id as cs_id
          FROM {member} as am
          LEFT JOIN {contract_subscription} as cs ON member_id = am.id
        WHERE cs.contract_id = " . $contract;
    if ($sContractIsVisible) {
      $query .= "
      UNION
          SELECT
          am.id as am_id,
          designation,
          '',
          '',
          '',"
        . $sTemp2 .
        "    ''
          FROM {member} as am
      WHERE status >= 2 AND designation NOT IN (
      SELECT
          designation
          FROM {member} as am
          LEFT JOIN {contract_subscription} as cs ON member_id = am.id
        WHERE cs.contract_id = " . $contract . "
      )";
    }
    $query .= "
      ORDER BY designation ASC
      ";
    $results = db_query($query);

//  Génération des lignes du tableau
    foreach ($results as $key => $value) {
      if ($value->sharedwith_member_id != "") {
        $iKey = array_search($results_am[$value->sharedwith_member_id], $results_am);
      } else {
        $iKey = 0;
      }
      $form['subscriptions'][$key]['member'] = array('#markup' => $value->designation);
      for ($i = 1; $i <= $iNumberOfQuantities; $i++) {
        $sTemp = 'quantity' . sprintf("%02d", $i);
        $form['subscriptions'][$key][$sTemp] = array(
          '#type' => 'number',
          '#min' => 0.00,
          '#step' => 0.50,
          '#default_value' => $value->$sTemp,
        );
      }
      $form['subscriptions'][$key]['sharedwith'] = array(
        '#type' => 'select',
        '#options' => $results_am,
        '#default_value' => $iKey
      );
      $form['subscriptions'][$key]['comment'] = array(
        '#type' => 'textarea',
        '#rows' => 1,
        '#default_value' => $value->comment,
      );

      $fileId = (int)$value->file__target_id;
      $title = ($fileId == 0) ? '000' : sprintf("%03d", $fileId);
      $form['subscriptions'][$key]['filehead'] = array(
        '#type' => 'details',
        '#title' => $title,
      );
      $form['subscriptions'][$key]['filehead']['file'] = array(
        '#type' => 'managed_file',
        '#upload_location' => 'private://contracts/subscriptions/',
        '#upload_validators' => array(
          'file_validate_extensions' => array('pdf'),
        ),
        '#default_value' => array($fileId),
      );

      $form['subscriptions'][$key]['am_id'] = array('#type' => 'hidden', '#default_value' => $value->am_id);
      $form['subscriptions'][$key]['cs_id'] = array('#type' => 'hidden', '#default_value' => $value->cs_id);
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    $form['#attached']['library'][] = 'amap/amap';
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $args = $form_state->getBuildInfo()['args'];
    $storage = \Drupal::entityTypeManager()->getStorage('contract_subscription');
    $iNumberOfQuantities = $form['subscriptions']['#quantities'];

    foreach ($form_state->getValue('subscriptions') as $key => $value) {
      $sQuantities = "";
      for ($i = 1; $i <= $iNumberOfQuantities; $i++) {
        $sTemp = 'quantity' . sprintf("%02d", $i);
        $sQuantities .= $value[$sTemp];
      }
      $id = $value['cs_id'];

      if ($id == "") {
        if ($sQuantities != "") {
          $entity = $storage->create();
          $sAction = 'C';
        } else {
          $sAction = '0';
        }
      } else {
        $entity = $storage->load($id);
        if ($sQuantities != "") {
          $sAction = 'M';
        } else {
          $sAction = 'S';
        }
      }
      switch ($sAction) {
        case 'C':
        case 'M':
          $entity->contract_id = $args;
          $entity->member_id = $value['am_id'];
          $entity->sharedwith_member_id = $value['sharedwith'];
          $entity->comment = $value['comment'];
          $entity->file = $value['filehead']['file'];
          for ($i = 1; $i <= $iNumberOfQuantities; $i++) {
            $sTemp = 'quantity' . sprintf("%02d", $i);
            $entity->$sTemp = $value[$sTemp];
          }
          $entity->save();
          break;
        case 'S':
          $entity->delete();
          break;
        default:
      }

    }

    _export_amap_CSV('amap_contracts_subscriptions', 'rest_export_1', $args[0]);

    $form_state->setRedirect('amap.contracts');
  }

}
