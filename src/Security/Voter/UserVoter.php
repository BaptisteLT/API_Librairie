<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


/*Ce voter permet de voir si l'utilisateur edit/voit bien bien SON compte et pas celui d'un autre user*/
class UserVoter extends Voter
{
    private $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject): bool
    {
        $supportsAttribute = in_array($attribute, ['USER_GET','USER_PUT','USER_PATCH']);
        $supportsSubject = $subject instanceof User;

        return $supportsAttribute && $supportsSubject;
    }

    /**
     * @param string $attribute
     * @param User $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** ... check if the user is anonymous ... **/

        switch ($attribute) {
            case 'USER_GET':
                if ( $this->security->getUser() == $subject || $this->security->isGranted('ROLE_ADMIN')) 
                { 
                    return true; 
                }  // only admins can view the user OR if its the right user
                break;
            case 'USER_PUT':
                if ( $this->security->getUser() == $subject || $this->security->isGranted('ROLE_ADMIN')) 
                { 
                    return true; // only admins can edit the user OR if its the right user
                }  
                break;
            case 'USER_PATCH':
                if ( $this->security->getUser() == $subject || $this->security->isGranted('ROLE_ADMIN')) 
                { 
                    return true; // only admins can edit the user OR if its the right user
                }  
                break;
        }

        return false;
    }
}