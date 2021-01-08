<?php

function amap_distribution_products()
{
  $values = [
    1 => 'Bœuf',
    2 => 'Brioche',
    3 => 'Champignons',
    4 => 'Farine',
    5 => 'Fruits rouges',
    6 => 'Lait Brebis',
    7 => 'Lait Vache',
    8 => 'Légumes',
    9 => 'Lentilles',
    10 => 'Miel',
    11 => 'Œufs',
    12 => 'Pain',
    13 => 'Patates',
    14 => 'Pommes Poires',
    15 => 'Porc',
    16 => 'Volaille',
    17 => 'Jus de pomme',
    18 => 'Agneau',
    19 => 'Cidre',
  ];
  return $values;
}

function amap_distribution_inscription_role()
{
  $values = [
    'D' => 'Distribution',
    'R' => 'Réserve',
    'X' => 'Référent',
  ];
  return $values;
}
