<?php

namespace App\Security\Voter;

use App\Entity\Projects;
use App\Entity\Users;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class ProjectVoter extends Voter
{
    public const string EDIT = 'PROJECTS_EDIT';
    public const string VIEW = 'PROJECTS_VIEW';

    public const string DELETE = 'PROJECTS_DELETE';

    public const string CREATE = 'PROJECTS_CREATE';


    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Projects;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof Users) {
            return false;
        }

        /**
         * @var Projects $project
         */
        $project = $subject;

        return match ($attribute) {
            self::EDIT => $this->canView($project, $user),
            self::VIEW => $this->canEdit($project, $user),
            self::DELETE => $this->canDelete($project, $user),
            self::CREATE =>$this->canCreate($project, $user),
            default => false,
        };
    }

    private function canView(Projects $project, Users $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles(), true)
            || $project->getUsers()->contains($user);
    }
    private function canEdit(Projects $project, Users $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles(), true)
            || $project->getUsers()->contains($user);
    }

    private function canDelete(Projects $project, Users $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles(), true)
            || $project->getUsers()->contains($user);
    }

    private function canCreate(Projects $project, Users $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles(), true)
            || $project->getUsers()->contains($user);
    }
}
