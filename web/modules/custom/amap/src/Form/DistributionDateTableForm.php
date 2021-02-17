<?php

namespace Drupal\amap\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DistributionDateTableForm.
 */
class DistributionDateTableForm extends FormBase
{


  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'distribution_date_table_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $form['distributions'] = [
      '#type' => 'table',
//    '#header' => array($this->t('Date')),
      '#header' => array(''),
      '#id' => 'calendarofdistributions',
      '#sticky' => TRUE,
    ];

    _list_distribution_products($aProducts, $sMin, $sMax);
    $fields = \Drupal::entityManager()->getBaseFieldDefinitions('distribution_date');
    foreach ($fields as $key => $value) {
      if ($key >= $sMin && $key <= $sMax) {
        // Remplacer le nom des champs product
        $i = (int)str_replace("product", "", $key);
        $newLabel = $aProducts[$i];
        $form['distributions']['#header'][] = array('data' => $newLabel,);
      }
    }

    $currentDay = date('Y-m-d');
    $sNextWed = strftime("%Y-%m-%d", strtotime("next Wednesday", strtotime("Yesterday")));

    $storage  = \Drupal::entityManager()->getStorage('distribution_date');
    $database = \Drupal::database();
    $query    = $database->select('distribution_date', 'amdd');
    $query->fields('amdd', ['id', 'distributiondate'])
      ->condition('distributiondate', $sNextWed, '>=')
      ->orderBy('distributiondate', 'ASC');
    $ids = $query->execute()->fetchCol(0);
    $dates = $storage->loadMultiple($ids);
    foreach ($dates as $id => $date) {
      foreach ($date as $key => $value) {
        $distributiondate = $date->distributiondate->value;
        $option = 0;
        switch (true) {
          case ($key == 'distributiondate'):
            $form['distributions'][$id]['distributiondate'] = [
              '#markup' => $distributiondate,
            ];
            break;
          case ($key >= $sMin && $key <= $sMax):
            $form['distributions'][$id][$key] = [
              '#type' => 'checkbox',
              '#default_value' => $date->$key->value,
              '#disabled' => ($distributiondate < $currentDay) ? TRUE : FALSE,
            ];
            break;
          default:
        }
      }
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    $form['#attached']['library'][] = 'amap/amap';

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

    _list_distribution_products($aProducts, $sMin, $sMax);
    foreach ($form_state->getValue('distributions') as $key => $value) {
      $entity = \Drupal::entityManager()->getStorage('distribution_date')->load($key);
      $entity->numberofproducts->value = 0;
      foreach ($entity as $key2 => $value2) {
        if ($key2 >= $sMin && $key2 <= $sMax) {
          $entity->numberofproducts->value += ($entity->$key2->value) ? 1 : 0;
          $entity->$key2->value = $value[$key2];
        }
      }
      $entity->save();
    }

    \Drupal::messenger()->addMessage($this->t('The changes have been saved.'));

    $form_state->setRedirect('view.amap_distributions.page_1');

  }

}
