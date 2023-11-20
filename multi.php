<?php
$achats = [
  "10:15" => [
      "Prenom" => "Fabien",
      "Prix" => 85,
      "Panier" => [
          "Fruits" => ["Kiwi", "Fraise", "Pomme"],
          "Legumes" => ["Salade", "PdT"]
      ]
  ],
    "10:25" => [
        "Prenom" => "Jérémy",
        "Prix" => 680,
        "Panier" => [
            "Fruits" => ["Oranges", "Lichi", "Ananas"],
            "Legumes" => ["Avocat", "Tomates"]
        ]
    ],
    "15:08" => [
        "Prenom" => "Thomas",
        "Prix" => 156,
        "Panier" => [
            "Fruits" => ["Clémentines", "Banane", "Pastèque"],
            "Legumes" => ["Carottes", "Concombres", "Courgettes"]
        ]
    ],
];
var_dump($achats);

// 1.	Afficher la liste de course de chaque acheteur dans des balises ul>li
//2.	Afficher le chiffre d’affaire final