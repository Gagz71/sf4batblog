<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Article;
use \DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        // Instanciation du faker
        $faker = Faker\Factory::create('fr_FR');

        // Création du compte administrateur
        $admin = new User();
        $admin
            ->setEmail('a@a.a')
            ->setRegistrationDate( new DateTime() )
            ->setPseudonym('Batman')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword( $this->encoder->encodePassword($admin, 'aaaaaaaaA7/') )
        ;

        $manager->persist($admin);

        // Création de 50 articles
        for($i = 0; $i < 50; $i++){
            $article = new Article();

            $article
                ->setPublicationDate( $faker->dateTimeBetween('-1 year', 'now') )
                ->setAuthor($admin)
                ->setTitle( $faker->sentence(10) )
                ->setContent( $faker->paragraph(15) )
            ;
    
            $manager->persist($article);
        }

        $manager->flush();
    }
}
