<?php
namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RightVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        // accepter tous les attributs, pour les comparer à la base
        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (! $user instanceof User) {
            return false;
        }

        try {
            return $user->hasRight($attribute);
        } catch (\Throwable $e) {
            // TEMPORAIRE : Log ou dump pour comprendre
            throw new \RuntimeException('Erreur dans hasRight(): ' . $e->getMessage(), 0, $e);
        }
    }
}
