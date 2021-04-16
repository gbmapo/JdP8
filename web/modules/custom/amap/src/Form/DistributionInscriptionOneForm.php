<?php

namespace Drupal\amap\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DistributionInscriptionOneForm.
 */
class DistributionInscriptionOneForm extends FormBase
{


  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'distribution_inscription_one_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $rows = \Drupal::service('listenrolments')->list();

    $form['rows'] = array(
      '#type' => 'value',
      '#value' => $rows,
    );

    $options = [];
    foreach ($rows as $key => $row) {
      $options[$key] = $row[0];
    }
    $date = $form_state->getValue('distributiondate');
    if ($date !== NULL) {
      $key = $date;
    } else {
      $key = 0;
    }
    $row = $rows[$key];

    $currentDay = date('Y-m-d');
    $currentUserRoles = \Drupal::currentUser()->getRoles();
    $bReferentDistrib = (in_array("referent_of_distribution", $currentUserRoles)) ? TRUE : FALSE;
    if ($row[0] < $currentDay) {
      $bDisabledD = TRUE;
      $bDisabledR = TRUE;
      $bDisabledX = TRUE;
    } else {
      $bDisabledD = (($row[1] == AMAP_AMAPIEN_PER_DISTRIBUTION && !$row[4])) || $row[6] || $row[7];
      $bDisabledR = (($row[2] == AMAP_RESERVE_PER_DISTRIBUTION && !$row[4])) || $row[5] || $row[7];
      $bDisabledX = !$bReferentDistrib || ($row[3] == AMAP_REFERENT_PER_DISTRIBUTION && !$row[4]) || $row[5] || $row[6];
    }

    $form['distributiondate'] = array(
      '#type' => 'select',
      '#options' => $options,
      '#title' => $this->t('Distribution Date'),
      '#required' => true,
      '#default_value' => $key,
      '#ajax' => [
        'callback' => '::dateCallback',
        'wrapper' => 'inscriptions',
      ],
    );

    $form['inscriptions'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'inscriptions'],
    ];

    $form['inscriptions']['distributiondate_id'] = array(
      '#type' => 'hidden',
      '#value' => $row[11]
    );

    $form['inscriptions']['D'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Distribution'),
      '#title_display' => 'before',
      '#value' => $row[5],
      '#attributes' => array(
        'onchange' => 'hasChanged(this)',
        'id' => 'inscriptions[' . $key . '][' . 'd]',
      ),
      '#disabled' => $bDisabledD
    );
    $form['inscriptions']['D2'] = array(
      '#type' => 'textfield',
      '#size' => 2,
      '#disabled' => true,
      '#value' => $row[1],
      '#attributes' => array(
        'id' => 'inscriptions[' . $key . '][' . 'd2]',
      ),
      '#suffix' => $row[8]
    );
    $form['inscriptions']['R'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Reserve'),
      '#title_display' => 'before',
      '#value' => $row[6],
      '#attributes' => array(
        'onchange' => 'hasChanged(this)',
        'id' => 'inscriptions[' . $key . '][' . 'r]',
      ),
      '#disabled' => $bDisabledR
    );
    $form['inscriptions']['R2'] = array(
      '#type' => 'textfield',
      '#size' => 2,
      '#disabled' => true,
      '#value' => $row[2],
      '#attributes' => array(
        'id' => 'inscriptions[' . $key . '][' . 'r2]',
      ),
      '#suffix' => $row[9]
    );
    $form['inscriptions']['X'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Referent'),
      '#title_display' => 'before',
      '#value' => $row[7],
      '#attributes' => array(
        'onchange' => 'hasChanged(this)',
        'id' => 'inscriptions[' . $key . '][' . 'x]',
      ),
      '#disabled' => $bDisabledX
    );
    $form['inscriptions']['X2'] = array(
      '#type' => 'textfield',
      '#size' => 2,
      '#disabled' => true,
      '#value' => $row[3],
      '#attributes' => array(
        'id' => 'inscriptions[' . $key . '][' . 'x2]',
      ),
      '#suffix' => $row[10]
    );

    $form['referent'] = array(
      '#type' => 'hidden',
      '#default_value' => ($bReferentDistrib) ? "Y" : "N",
      '#attributes' => array(
        'id' => 'breferentdistrib',
      ),
    );

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    $form['#attached']['library'][] = 'amap/amap';

    $form['#attached']['drupalSettings']['myConstants'] = [
      'nbmaxD' => AMAP_AMAPIEN_PER_DISTRIBUTION,
      'nbmaxR' => AMAP_RESERVE_PER_DISTRIBUTION,
      'nbmaxX' => AMAP_REFERENT_PER_DISTRIBUTION,
    ];

    return $form;

  }

  public function dateCallback($form, FormStateInterface $form_state)
  {
    return $form['inscriptions'];
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

    $iCurrentUserId = \Drupal::currentUser()->id();
    $distributiondate_id = $form_state->getUserInput()['distributiondate_id'];

    \Drupal::database()
      ->delete('distribution_inscription')
      ->condition('amapien_id', $iCurrentUserId)
      ->condition('distributiondate_id', $distributiondate_id)
      ->execute();

    foreach ($form_state->getUserInput() as $key => $value) {
      switch ($key) {
        case 'distributiondate_id':
//        $distributiondate_id = $value; Nothing to do: already set
          break;
        case 'D':
        case 'R':
        case 'X':
          if ($value == 1) {
            $data = array(
              'distributiondate_id' => $distributiondate_id,
              'amapien_id' => $iCurrentUserId,
              'role' => $key
            );
            $entity =  \Drupal::entityTypeManager()
              ->getStorage('distribution_inscription')
              ->create($data);
            $entity->save();
          }
          break;
        default:
      }
    }
    \Drupal::messenger()->addMessage($this->t('The changes have been saved.'));

  }

}
