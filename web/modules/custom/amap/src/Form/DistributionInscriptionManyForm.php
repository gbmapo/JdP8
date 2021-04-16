<?php

namespace Drupal\amap\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Class DistributionInscriptionManyForm.
 */
class DistributionInscriptionManyForm extends FormBase
{


  public function getFormId()
  {
    return 'distribution_inscription_many_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $oCurrentUser = \Drupal::currentUser();

    if ($oCurrentUser->hasPermission('add distribution date entities')) {
      $data = [
        '#type'     => 'inline_template',
        '#template' => '<input type="submit" id="edit-toggle" name="toggleButton" value="{{ foo }}" class="button js-form-submit form-submit">',
        '#context'  => ['foo' => $this->t('Toggle')],
      ];
    }
    else {
      $data = '';
    }

    $form['inscriptions'] = [
      '#type'   => 'table',
      '#header' => [
        [
          'data'    => $data,
          'colspan' => 2,
        ],
        [
          'data'    => 'Distribution',
          'colspan' => 2,
        ],
        [
          'data'    => 'Réserve',
          'colspan' => 2,
        ],
        [
          'data'    => 'Référent',
          'colspan' => 2,
        ],
      ],
      '#id'     => 'planningofdistributions',
      '#sticky' => TRUE,
    ];

    $currentDay = date('Y-m-d');
    $currentUserRoles = $oCurrentUser->getRoles();
    $bReferentDistrib = (in_array("referent_of_distribution", $currentUserRoles)) ? TRUE : FALSE;

    if (isset($form_state->getStorage()['toggle'])) {
      $filter = $form_state->getStorage()['toggle'];
    }
    else {
      $filter = '';
      $form_state->set('toggle', '');
    }
    $service = \Drupal::service('listenrolments');
    $service->setFilter($filter);

    $rows = $service->list();
    foreach ($rows as $key => $row) {

      if ($row[0] < $currentDay) {
        $bDisabledD = TRUE;
        $bDisabledR = TRUE;
        $bDisabledX = TRUE;
      }
      else {
        $bDisabledD = (($row[1] == AMAP_AMAPIEN_PER_DISTRIBUTION && !$row[4])) || $row[6] || $row[7];
        $bDisabledR = (($row[2] == AMAP_RESERVE_PER_DISTRIBUTION && !$row[4])) || $row[5] || $row[7];
        $bDisabledX = !$bReferentDistrib || ($row[3] == AMAP_REFERENT_PER_DISTRIBUTION && !$row[4]) || $row[5] || $row[6];
      }
      setlocale(LC_TIME, "fr_FR.UTF8");
      $sDate = strftime("%d/%m/%Y", strtotime(substr($row[0], 5, 2) . "/" . substr($row[0], 8, 2) . "/" . substr($row[0], 0, 4)));

      $form['inscriptions'][$key]['distributiondatelong'] = [
        '#markup' => $sDate,
      ];
      $form['inscriptions'][$key]['distributiondate_id'] = [
        '#type'  => 'hidden',
        '#value' => $row[11],
      ];
      $form['inscriptions'][$key]['D'] = [
        '#type'          => 'checkbox',
        '#default_value' => $row[5],
        '#attributes'    => [
          'id'       => 'inscriptions[' . $key . '][' . 'd]',
          'onchange' => 'hasChanged(this)',
        ],
        '#disabled'      => $bDisabledD,
      ];
      $form['inscriptions'][$key]['D2'] = [
        '#type'          => 'textfield',
        '#size'          => 2,
        '#disabled'      => true,
        '#default_value' => $row[1],
        '#attributes'    => [
          'id' => 'inscriptions[' . $key . '][' . 'd2]',
        ],
        '#suffix'        => $row[8],
      ];
      $form['inscriptions'][$key]['R'] = [
        '#type'          => 'checkbox',
        '#default_value' => $row[6],
        '#attributes'    => [
          'id'       => 'inscriptions[' . $key . '][' . 'r]',
          'onchange' => 'hasChanged(this)',
        ],
        '#disabled'      => $bDisabledR,
      ];
      $form['inscriptions'][$key]['R2'] = [
        '#type'          => 'textfield',
        '#size'          => 2,
        '#disabled'      => true,
        '#default_value' => $row[2],
        '#attributes'    => [
          'id' => 'inscriptions[' . $key . '][' . 'r2]',
        ],
        '#suffix'        => $row[9],
      ];
      $form['inscriptions'][$key]['X'] = [
        '#type'          => 'checkbox',
        '#default_value' => $row[7],
        '#attributes'    => [
          'id'       => 'inscriptions[' . $key . '][' . 'x]',
          'onchange' => 'hasChanged(this)',
        ],
        '#disabled'      => $bDisabledX,
      ];
      $form['inscriptions'][$key]['X2'] = [
        '#type'          => 'textfield',
        '#size'          => 2,
        '#disabled'      => true,
        '#default_value' => $row[3],
        '#attributes'    => [
          'id' => 'inscriptions[' . $key . '][' . 'x2]',
        ],
        '#suffix'        => $row[10],
      ];
    }

    $form['referent'] = [
      '#type'          => 'hidden',
      '#default_value' => ($bReferentDistrib) ? "Y" : "N",
    ];
    $form['referent']['#attributes']['id'] = 'breferentdistrib';

    $form['submit'] = [
      '#type'  => 'submit',
      '#name'  => 'submit',
      '#value' => $this->t('Submit'),
    ];

    $form['#attached']['library'][] = 'amap/amap';

    $form['#attached']['drupalSettings']['myConstants'] = [
      'nbmaxD' => AMAP_AMAPIEN_PER_DISTRIBUTION,
      'nbmaxR' => AMAP_RESERVE_PER_DISTRIBUTION,
      'nbmaxX' => AMAP_REFERENT_PER_DISTRIBUTION,
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    parent::validateForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {

    $input = $form_state->getUserInput();
    if (isset($input['toggleButton'])) {
      $toggle = ($form_state->getStorage()['toggle'] == 'all') ? '' : 'all';
      $form_state->set('toggle', $toggle);
      $form_state->setRebuild();
    }
    else {

      $iCurrentUserId = \Drupal::currentUser()->id();

      $query = \Drupal::database()->delete('distribution_inscription');
      $query->condition('amapien_id', $iCurrentUserId);
      $query->execute();

      foreach ($form_state->getValue('inscriptions') as $key => $value) {
        foreach ($value as $key2 => $value2) {
          switch ($key2) {
            case 'distributiondate_id':
              $distributiondate_id = $value2;
              break;
            case 'D':
            case 'R':
            case 'X':
              if ($value2 == 1) {
                $data = [
                  'distributiondate_id' => $distributiondate_id,
                  'amapien_id'          => $iCurrentUserId,
                  'role'                => $key2,
                ];
                $entity = \Drupal::entityTypeManager()
                  ->getStorage('distribution_inscription')
                  ->create($data);
                $entity->save();
              }
              break;
            default:
          }
        }
      }

      \Drupal::messenger()->addMessage($this->t('The changes have been saved.'));

    }
  }

}



