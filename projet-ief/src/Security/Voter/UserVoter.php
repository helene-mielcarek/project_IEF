<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'DELETE', 'VALIDATE','VIEW'])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
            case 'VIEW':
                if($user == $subject){
                    return true;
                }
                break;
            case 'DELETE':
                if($user == $subject||
                in_array('ROLE_ADMIN', $user->getRoles())){
                    return true;
                }
                break;
            case 'VALIDATE':
                if(in_array('ROLE_ADMIN', $user->getRoles())){
                    return true;
                }
                break;
        }

        return false;
    }
}
