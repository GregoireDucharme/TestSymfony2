<?php

namespace LBC\ListeAnnonceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;
use LBC\ListeAnnonceBundle\Entity\Annonce;
use LBC\ListeAnnonceBundle\Entity\Commentaire;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class AnnonceController extends Controller
{
    public function indexAction($id, Request $request)
    {
    	$manager = $this->getDoctrine()->getManager();
		$annonceRepo = $manager->getRepository('LBCListeAnnonceBundle:Annonce');
		$commentaireRepo = $manager->getRepository('LBCListeAnnonceBundle:Commentaire');
		$annonce = $annonceRepo->find($id);
		$commentaires = $commentaireRepo->findBy(array('annonce' => $annonce));
		$prevAnnonce = null;
		$nextAnnonce = null;
		$prevAnnonce = $annonceRepo->find($id - 1);
		$nextAnnonce = $annonceRepo->find($id + 1);


		// Creation formulaire ajout commentaire
	    $commentaire = new commentaire();
	    $formBuilder = $this->get('form.factory')->createBuilder('form', $commentaire);
	    $formBuilder
	      ->add('utilisateur',     'text')
	      ->add('contenu',   'textarea')
	      ->add('Publier',      'submit');
	    $form = $formBuilder->getForm();


	    // Gère la réponse du formulaire
	    if ($annonce != null && $form->handleRequest($request)->isValid()) {
	    	$commentaire->setAnnonce($annonce);
	    	$manager->persist($commentaire);
	    	$manager->flush();
		    $request->getSession()->getFlashBag()->add('notice', 'Commentaire publié.');
		    return $this->redirect($this->generateUrl('lbc_annonce', array('id' => $id)));
		}
        return new Response($this->renderView("LBCListeAnnonceBundle:Annonce:contentAnnonce.html.twig", array('prevAnnonce' => $prevAnnonce, 'nextAnnonce' => $nextAnnonce, 'annonce' => $annonce,
        	'commentaires' => $commentaires, 'form' => $form->createView())));
    }
}
