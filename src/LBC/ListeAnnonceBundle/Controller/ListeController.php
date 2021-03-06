<?php

namespace LBC\ListeAnnonceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;
use LBC\ListeAnnonceBundle\Entity\Annonce;	

class ListeController extends Controller
{
	private function getImage($crawler)
	{
		// Récupère l'url de l'image de l'annonce
		$node = $crawler->filter(".image-and-nb > img");
		$imageUrl = "";
		if (count($node) > 0)
		{
			$imageUrl = $node->attr("src");
		}
		return $imageUrl;
	}

	private function fillAnnonceRepo($manager, &$annonces)
	{
		$numeroPage = 1;
		$bddUpToDate = false;
		$count = count($annonces);
		$initRepo = false;
		if ($count == 0)
			$initRepo = true;
		$countNew = 0;
		$newAnnonces = array();

		// Atualise ou rempli pour la premiere fois la BDD
		while (!$bddUpToDate)
		{
			// Url contenant les informations a récuperer
			$lbcUrl = "http://www.leboncoin.fr/animaux/offres/rhone_alpes/?o=" . $numeroPage;
			$content = file_get_contents($lbcUrl);
			$crawler = new Crawler($content);
			$classListLbc = "list-lbc";

			// Parsing de chaque div contenant chaque annonce
			foreach ($crawler->filter('.lbc') as $lbcAnnonce)
			{
				$tmpCrawler = new Crawler($lbcAnnonce);
				$infoNeeded = array("title" => "", "placement" => "", "price" => "", "date" => "");
				foreach ($infoNeeded as $key => $value)
				{
					$tmpNode = $tmpCrawler->filter('.' . $key);
					if (count($tmpNode) > 0)
						$infoNeeded[$key] = $tmpCrawler->filter('.' . $key)->text();
				}
				$lbcUrl = $tmpCrawler->parents()->attr("href"); 
				$newAnnonce = new Annonce($infoNeeded['title'], $infoNeeded['placement'], $infoNeeded['price'], $infoNeeded['date'], $lbcUrl ,$this->getImage($tmpCrawler));

				// Si actualisation de la BDD, verification de l'existence ou non de l'annonce, si oui, fin de la boucle
				if (!$initRepo)
					foreach ($annonces as $annonce)
					{
						if ($annonce->Compare($newAnnonce))
						{
							$bddUpToDate = true;
							break;
						}
					}
				if ($bddUpToDate == true)
					break;
				// Sinnon stockage de l'annonce
				$newAnnonces[] = $newAnnonce;
				$count++;
				$countNew++;
				if ($countNew > 100)
				{
					$bddUpToDate = true;
					break;
				}
				$manager->persist($newAnnonce);
			}
			$numeroPage++;
		}

		// Si actualisation de la BDD, vérification du nombre d'article, si celui-ci est supérieur a 100 suppresion des plus anciens
		if ($initRepo == false)
		{
			$annonces = array_merge($newAnnonces, $annonces);
			$count = count($annonces);
			if ($count > 100) {
				$count = 100;
				while (isset($annonces[$count]))
				{
					$manager->remove($annonces[$count++]);
				}
			}
		}
	}

    public function indexAction()
    {
    	$manager = $this->getDoctrine()->getManager();
		$annonceRepo = $manager->getRepository('LBCListeAnnonceBundle:Annonce');
		$annonces = $annonceRepo->findAll(array("date_creation" => "asc"));
    	$this->fillAnnonceRepo($manager, $annonces);
		$manager->flush();
		$annonces = $annonceRepo->findAll();
        return new Response($this->renderView("LBCListeAnnonceBundle:Liste:contentListeAnnonce.html.twig", array('annonces' => $annonces)));
    }
}
