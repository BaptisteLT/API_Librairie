<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Editeur;
use App\Entity\Emprunt;
use App\Entity\Exemplaire;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * L4encodeur de mots de passe
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');


        for($l = 0; $l<25;$l++)
        {

            $user = new User();

            $hash = $this->encoder->encodePassword($user,'password');

            $user->setEmail('test@test'.strval($l).'.fr')
                ->setRoles($faker->randomElement([['ROLE_USER']]))
                ->setPassword($hash)
                ->setEtablissement($faker->company)
                ->setClasse($faker->randomElement(['1B','1C','2B','6D','4D','5E']))
                ->setNom($faker->name())
                ->setPrenom($faker->firstName())
                ->setDateNaissance($faker->dateTime())
                ->setGenre($faker->randomElement([0,1]))
                ->setAdresse($faker->address)
                ->setCreatedAt($faker->dateTime());

            $manager->persist($user);

            $livre = new Livre();

            $auteur = new Auteur();
            $auteur->setNom($faker->name())
                   ->setPrenom($faker->firstName())
                   ->setDateNaissance($faker->dateTime());

            $manager->persist($auteur);


            $editeur = new Editeur();
            $editeur->setNom($faker->name());

            $manager->persist($editeur);


            $genre = new Genre();
            $genre->setType('Type');

            $manager->persist($genre);




            $livre->setTitre("Titre du livre")
                  ->setNbPages(rand(1,5))
                  ->setAnneePublication($faker->dateTime())
                  ->setAuteur($auteur)
                  ->setGenre($genre)
                  ->setEditeur($editeur);

            $numEx=0;

            for($e = 0; $e<rand(1,4);$e++)
            {


                $numEx = $numEx+ 1;
                $exemplaire=new Exemplaire;
                $exemplaire->setNumExemplaire($numEx);
                $exemplaire->setLivre($livre);
                
                $manager->persist($exemplaire);

                $emprunt=new Emprunt;
                $emprunt->setCreatedAt($faker->dateTime());
                $emprunt->setUser($user);
                $emprunt->setExemplaire($exemplaire);
                $emprunt->setRendu(0);

                $manager->persist($emprunt);


            }
            $numEx=0;

            $manager->persist($livre);
        }


        for($l = 0; $l<25;$l++)
        {

            $userAdmin = new User();

            $hash = $this->encoder->encodePassword($userAdmin,'password');

            $userAdmin->setEmail('testAdmin@test'.strval($l).'.fr')
                ->setRoles($faker->randomElement([['ROLE_ADMIN']]))
                ->setPassword($hash)
                ->setEtablissement($faker->company)
                ->setClasse($faker->randomElement(['1B','1C','2B','6D','4D','5E']))
                ->setNom($faker->name())
                ->setPrenom($faker->firstName())
                ->setDateNaissance($faker->dateTime())
                ->setGenre($faker->randomElement([0,1]))
                ->setAdresse($faker->address)
                ->setCreatedAt($faker->dateTime());

            $manager->persist($userAdmin);

            $livre = new Livre();

            $auteur = new Auteur();
            $auteur->setNom($faker->name())
                   ->setPrenom($faker->firstName())
                   ->setDateNaissance($faker->dateTime());

            $manager->persist($auteur);


            $editeur = new Editeur();
            $editeur->setNom($faker->name());

            $manager->persist($editeur);


            $genre = new Genre();
            $genre->setType('Type');

            $manager->persist($genre);




            $livre->setTitre("Titre du livre")
                  ->setNbPages(rand(1,5))
                  ->setAnneePublication($faker->dateTime())
                  ->setAuteur($auteur)
                  ->setGenre($genre)
                  ->setEditeur($editeur);

            $numEx=0;

            for($e = 0; $e<rand(1,4);$e++)
            {


                $numEx = $numEx+ 1;
                $exemplaire=new Exemplaire;
                $exemplaire->setNumExemplaire($numEx);
                $exemplaire->setLivre($livre);
                
                $manager->persist($exemplaire);

                $emprunt=new Emprunt;
                $emprunt->setCreatedAt($faker->dateTime());
                $emprunt->setUser($userAdmin);
                $emprunt->setExemplaire($exemplaire);
                $emprunt->setRendu(0);

                $manager->persist($emprunt);


            }
            $numEx=0;

            $manager->persist($livre);
        }
        $auteurUnique = new Auteur();
        $auteurUnique->setNom($faker->name())
               ->setPrenom($faker->firstName())
               ->setDateNaissance($faker->dateTime());

        $manager->persist($auteurUnique);
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
