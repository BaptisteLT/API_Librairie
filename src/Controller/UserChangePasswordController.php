<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserChangePasswordController
{
    /**
     * Doctrine Entity Manager
     *
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * User repository
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Password encoder
     *
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
 

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $manager,UserRepository $userRepository)
    {
        $this->manager = $manager;
        $this->userRepository=$userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function __invoke(User $data)
    {
        //Récupère le mot de passe tapé par l'utilisateur
        $oldPassword=$data->getOldPassword();
        //Récupère le nouveau mot de passe tapé par l'utilisateur
        $newPassword=$data->getNewPassword();
        
        //Récupère l'Id de l'utilisateur
        $idUser=$data->getId();

        //Grâce à l'Id de l'utilisateur on récupère son entité User.
        $user=$this->userRepository->find($idUser);

        //On récupère ainsi le mot de passe du compte
        $currentPassword=$user->getPassword();
        
        //Et on le compare avec le mot de passe tapé par l'utilisateur. Cela retourne TRUE ou FALSE si les mots de passe sont égaux
        $match = $this->passwordEncoder->isPasswordValid($user,$oldPassword);
        
        //Donc si true
        if($match)
        {
            //On encode le password
            $data->setPassword($this->passwordEncoder->encodePassword($data,$data->getNewPassword()));
             
            //On le flush en base de données
            $this->manager->flush();
            

            $response = new Response(
                'Mot de passe modifié avec succès.',
                Response::HTTP_OK,
                ['content-type' => 'text/html']
            );

            return $response;
        }
        
        return new Response('Mauvais mot de passe.', 401);
    }
}