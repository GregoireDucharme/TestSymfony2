<?php

namespace LBC\ListeAnnonceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;
use LBC\ListeAnnonceBundle\Entity\Annonce;	
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AnnonceController extends Controller
{
    public function indexAction($id)
    {
    	$manager = $this->getDoctrine()->getManager();
		$annonceRepo = $manager->getRepository('LBCListeAnnonceBundle:Annonce');
		$annonce = $annonceRepo->find($id);
		if ($id > 1)
			$prevAnnonce = $annonceRepo->find($id - 1);
		if ($id < 99)
			$nextAnnonce = $annonceRepo->find($id + 1);
		if ($annonce === null)
		{
			throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas. Contactez via un courriel l'administrateur du site.");
		}
        return new Response($this->renderView("LBCListeAnnonceBundle:Annonce:contentAnnonce.html.twig", array('prevAnnonce' => $prevAnnonce, 'nextAnnonce' => $nextAnnonce, 'annonce' => $annonce)));
    }
}
