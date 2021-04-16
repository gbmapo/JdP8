<?php

namespace Drupal\amap;

/**
 * Class ListEnrolments.
 */
class ListEnrolments
{

  private $filter;

  /**
   * Constructs a new ListEnrolments object.
   */
  public function __construct()
  {
  }

  public function setFilter($value)
  {
    $this->filter = $value;
    return $this;
  }

  public function list()
  {

    $iCurrentUserId = \Drupal::currentUser()->id();

    $sNextWed = ($this->filter == 'all') ? '' : strftime("%Y-%m-%d", strtotime("next Wednesday", strtotime("Yesterday")));
    $database = \Drupal::database();
    $query = $database->select('distribution_date', 'amdd');
    $query->leftJoin('distribution_inscription', 'amdi', 'amdi.distributiondate_id = amdd.id');
    $query->leftJoin('person', 'ap', 'ap.id = amdi.amapien_id');
    $query->fields('amdd', ['id', 'distributiondate', 'numberofproducts'])
      ->fields('amdi', ['id', 'distributiondate_id', 'amapien_id', 'role'])
      ->fields('ap', ['id', 'lastname', 'firstname'])
      ->condition('numberofproducts', 0, '>')
      ->condition('distributiondate', $sNextWed, '>=')
      ->orderBy('distributiondate', 'ASC')
      ->orderBy('role', 'ASC')
      ->orderBy('lastname', 'ASC')
      ->orderBy('firstname', 'ASC');
    $results = $query->execute();
    $rows = [];
    $sDateSav = '';
    foreach ($results as $key => $result) {
      $sDate = $result->distributiondate;
      $sNomPrenom = "";
      $amapienid = $result->amapien_id;
      if ($amapienid) {
        $amapien = \Drupal::entityTypeManager()->getStorage('person')->load($amapienid);
        $sNomPrenom = (!is_null($amapien)) ? $amapien->label() : $amapienid;
      }
      if ($sDate != $sDateSav) {
        $row = [];
        $row[0] = $sDate;       // Date de distribution
        $row[1] = 0;            // Nombre d'inscrits Distribution
        $row[2] = 0;            // Nombre d'inscrits Réserve
        $row[3] = 0;            // Nombre d'inscrits Référent
        $row[4] = FALSE;        // L'utilisateur actif est inscrit
        $row[5] = FALSE;        // L'utilisateur actif est inscrit Distribution
        $row[6] = FALSE;        // L'utilisateur actif est inscrit Réserve
        $row[7] = FALSE;        // L'utilisateur actif est inscrit Référent
        $row[8] = '';           // 'Nom Prénom' des inscrits
        $row[9] = '';           // 'Nom Prénom' des inscrits
        $row[10] = '';           // 'Nom Prénom' des inscrits
        $row[11] = $result->id;  // id de la date dans la table
        $rows[] = $row;
        $sDateSav = $sDate;
      }
      $iRow = count($rows) - 1;
      switch ($result->role) {
        case "D":
          $rows[$iRow][1]++;
          if ($amapienid == $iCurrentUserId) {
            $rows[$iRow][4] = TRUE;
            $rows[$iRow][5] = TRUE;
          }
          $rows[$iRow][8] .= $sNomPrenom . "<BR>";
          break;
        case "R":
          $rows[$iRow][2]++;
          if ($amapienid == $iCurrentUserId) {
            $rows[$iRow][4] = TRUE;
            $rows[$iRow][6] = TRUE;
          }
          $rows[$iRow][9] .= $sNomPrenom . "<BR>";
          break;
        case "X":
          $rows[$iRow][3]++;
          if ($amapienid == $iCurrentUserId) {
            $rows[$iRow][4] = TRUE;
            $rows[$iRow][7] = TRUE;
          }
          $rows[$iRow][10] .= $sNomPrenom . "<BR>";
          break;
        default:
      }
    }

    return $rows;

  }

}
