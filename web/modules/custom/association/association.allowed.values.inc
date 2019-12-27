<?php

function association_member_country()
{
  $countries = \Drupal::service('country_manager')->getList();
  return $countries;
}

function association_member_status()
{
  $values = [
    0 => 'Ancien',
    1 => 'Non renouvelée',
    2 => 'En attente',
    3 => 'Renouvelée',
    4 => 'Actif',
  ];
  return $values;
}
