<?php

// L'espace de nom d'un fichier dans SF doit être toujours le même que son placement physique dans le dossier "src". App = src
// Ex : si le fichier se trouve dans "src/Entity/User.php", alors le namespace de ce fichier sera App\Entity;
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use App\Entity\Comment;
use \DateTime;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Liste des contrôleurs des pages principales du site web. Le nom doit être toujours identique au nom du fichier.
 */
class MainController extends AbstractController
{

    /**
     * @Route("/", name="main_home")
     */
    public function home()
    {
        $trst = new Response
        return $this->render('main/home.html.twig');
    }

    /**
     * @Route("/mon-profil/", name="main_profil")
     * @Security("is_granted('ROLE_USER')")
     */
    public function profil()
    {
        return $this->render('main/profil.html.twig');
    }

}