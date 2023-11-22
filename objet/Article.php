<?php
namespace objet;
class Article {
    private ?int $Id = null;
    private ?String $Titre = null;
    private ?String $Description = null;
    private ?String $Auteur = null;
    private ?\DateTime $DatePublication = null;
    private ?String $ImageRepository = null;
    private ?String $ImageFileName = null;

    public function getId(): ?int
    {
        return $this->Id;
    }

    public function setId(?int $Id): Article
    {
        $this->Id = $Id;
        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(?string $Titre): Article
    {
        $this->Titre = $Titre;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): Article
    {
        $this->Description = $Description;
        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->Auteur;
    }

    public function setAuteur(?string $Auteur): Article
    {
        $this->Auteur = $Auteur;
        return $this;
    }

    public function getDatePublication(): ?\DateTime
    {
        return $this->DatePublication;
    }

    public function setDatePublication(?\DateTime $DatePublication): Article
    {
        $this->DatePublication = $DatePublication;
        return $this;
    }

    public function getImageRepository(): ?string
    {
        return $this->ImageRepository;
    }

    public function setImageRepository(?string $ImageRepository): Article
    {
        $this->ImageRepository = $ImageRepository;
        return $this;
    }

    public function getImageFileName(): ?string
    {
        return $this->ImageFileName;
    }

    public function setImageFileName(?string $ImageFileName): Article
    {
        $this->ImageFileName = $ImageFileName;
        return $this;
    }

    public function premiersMots(int $n) : String {
        preg_match('/^(\S+\s+){0,' . ($n - 1) . '}\S+/', $this->Description, $matches);
        $resultat = $matches[0];
        return $resultat;
    }

    public function strlen(){
        return \strlen($this->Titre) + 1;
    }

    public function SqlAdd(\PDO $bdd) :array{
        try{
            $requete = $bdd->prepare("INSERT INTO articles (Titre, Description, DatePublication, Auteur, ImageRepository, ImageFileName) VALUES(:Titre, :Description, :DatePublication, :Auteur, :ImageRepository, :ImageFileName)");

            $execute = $requete->execute([
                "Titre" => $this->getTitre(),
                "Description" => $this->getDescription(),
                "DatePublication" => $this->getDatePublication()->format("Y-m-d"),
                "Auteur" => $this->getAuteur(),
                "ImageRepository" => $this->getImageRepository(),
                "ImageFileName" => $this->getImageFileName(),
            ]);
            return [0, "Insertion OK"];
        }catch (\Exception $e){
            return [1, $e->getMessage()];
        }

    }
}
