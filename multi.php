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

$prixTotal = 0;

echo "<ul>";
    foreach ($achats as $heure => $detail) {
        $prixTotal += $detail["Prix"];
        echo "<li>Voici le panier de {$detail["Prenom"]}";
            echo"<ul>";
            foreach ($detail["Panier"]["Fruits"] as $type => $produit){
                echo "$produit, ";
            }
            echo "<br/>";
        foreach ($detail["Panier"]["Legumes"] as $type => $produit){
            echo "$produit, ";
        }
            echo"</ul>";
        echo"</li>";
    }

echo "</ul>";
echo "<p>LE chiffre d'affaire est de $prixTotal €</p>";