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
		$prevAnnonce = null;
		$nextAnnonce = null;
		$prevAnnonce = $annonceRepo->find($id - 1);
		$nextAnnonce = $annonceRepo->find($id + 1);
        return new Response($this->renderView("LBCListeAnnonceBundle:Annonce:contentAnnonce.html.twig", array('prevAnnonce' => $prevAnnonce, 'nextAnnonce' => $nextAnnonce, 'annonce' => $annonce)));
    }
}
