<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Form\NewArticleFormType;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use \DateTime;

/**
 * Tous les contrôleurs de BlogController commenceront leur URL avec /blog et leur nom par blog_
 * 
 * @Route("/blog", name="blog_")
 */
class BlogController extends AbstractController
{

    /**
     * @Route("/nouvelle-publication/", name="new_publication")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function newPublication(Request $request)
    {

        // Création d'un article vide
        $article = new Article();

        // Création d'un nouveau formulaire basé sur "NewArticleFormType", qui hydratera notre article "$article"
        $form = $this->createForm(NewArticleFormType::class, $article);

        // Remplissage du traitement du formulaire avec les données POST (sous forme d'objet $request)
        $form->handleRequest($request);

        // Si le formulaire a été envoyé et si il ne contient aucune erreur (toutes les contraintes ont été respectées)
        if($form->isSubmitted() && $form->isValid()){

            // Récupération de l'utilisateur actuellement connecté
            $userConnected = $this->getUser();

            // Hydratation de la date de publication et de l'auteur de l'article
            $article
                ->setPublicationDate(new DateTime())
                ->setAuthor( $userConnected )
            ;

            // Récupération du manager général des entités
            $em = $this->getDoctrine()->getManager();

            // Persistance de l'article auprès de Doctrine
            $em->persist($article);

            // Application de la sauvegarde en bdd
            $em->flush();

            // Création d'un message flash pour afficher le succès de la création de l'article
            $this->addFlash('success', 'Article publié avec succès !');

            // TODO: changer pour mettre une redirection vers la page d'affichage du nouvel article
            // Redirige sur une autre page qui affichera le message flash de succès
            return $this->redirectToRoute('main_home');

        }

        // On appelle la vue en lui transmettant l'affichage du formulaire dans une variable "form"
        return $this->render('blog/newPublication.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/publications/liste/", name="publication_list")
     */
    public function publicationList()
    {

        // Récupération de tous les articles
        $articleRepo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $articleRepo->findAll();

        // Appel de la vue en lui envoyant les articles
        return $this->render('blog/publicationList.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/publication/{slug}/", name="publication_view")
     */
    public function publicationView(Article $article, Request $request)
    {
        
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        // Appel de la vue en envoyant l'article qui sera affiché dessus
        return $this->render('blog/publicationView.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView(),
        ]);
    }

}
