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
				$infoNeeded = array("title" => "", "placement" => "", "price" => "");
				foreach ($infoNeeded as $key => $value)
				{
					$tmpNode = $tmpCrawler->filter('.' . $key);
					if (count($tmpNode) > 0)
						$infoNeeded[$key] = $tmpCrawler->filter('.' . $key)->text();
				}
				$newAnnonce = new Annonce($infoNeeded['title'], $infoNeeded['placement'], $infoNeeded['price'], $this->getImage($tmpCrawler));

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

				$newAnnonces[] = $newAnnonce;
				$count++;
				$countNew++;
				if ($countNew > 100 || ($count > 100 && $initRepo == true))
				{
					$bddUpToDate = true;
					break;
				}
				$manager->persist($newAnnonce);
			}
			$numeroPage++;
		}

		// Si actualisation de la BDD, vérification du nombre d'article, si celui-ci est supérieur a 100 suppresion des plus anciens
		if (!$initRepo)
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
		$annonces = $annonceRepo->findAll();
    	$this->fillAnnonceRepo($manager, $annonces);
		$manager->flush();
		$annonces = $annonceRepo->findAll();
        return new Response($this->renderView("LBCListeAnnonceBundle:Liste:content.html.twig", array('annonces' => $annonces)));
    }
}
