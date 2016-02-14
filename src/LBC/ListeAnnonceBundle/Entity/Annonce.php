<?php

namespace LBC\ListeAnnonceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Annonce
 *
 * @ORM\Table(name="annonce")
 * @ORM\Entity(repositoryClass="LBC\ListeAnnonceBundle\Repository\AnnonceRepository")
 */
class Annonce
{
    function __construct($title, $placement, $price, $date, $lbcUrl, $imageUrl)
    {
        $this->titre = $title;
        $this->lieu = trim($placement);
        $this->prix = (int)$price;
        $this->date = $date;
        $this->lbcUrl = $lbcUrl;
        $this->image = $imageUrl;
        $this->date_creation = new \Datetime();
    }
    public function Compare($annonceToCompare)
    {
        if ($this->titre == $annonceToCompare->titre && $this->prix == $annonceToCompare->prix && $this->lieu == $annonceToCompare->lieu)
            return (true);
        return (false);
    }
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="string", length=255)
     */
    private $lieu;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer")
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="lbcurl", type="string", length=255)
     */
    private $lbcUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255)
     */
    private $date;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="date_creation", type="date", length=255)
     */
    private $date_creation;

    /**
     * Get id
     *
     * @return integer 
     */

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Annonce
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     * @return Annonce
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string 
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set prix
     *
     * @param integer $prix
     * @return Annonce
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return integer 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Annonce
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set date
     *
     * @param string $date
     * @return Annonce
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set lbcUrl
     *
     * @param string $lbcUrl
     * @return Annonce
     */
    public function setLbcUrl($lbcUrl)
    {
        $this->lbcUrl = $lbcUrl;

        return $this;
    }

    /**
     * Get lbcUrl
     *
     * @return string 
     */
    public function getLbcUrl()
    {
        return $this->lbcUrl;
    }

    /**
     * Set date_creation
     *
     * @param string $dateCreation
     * @return Annonce
     */
    public function setDateCreation($dateCreation)
    {
        $this->date_creation = $dateCreation;

        return $this;
    }

    /**
     * Get date_creation
     *
     * @return string 
     */
    public function getDateCreation()
    {
        return $this->date_creation;
    }
}
