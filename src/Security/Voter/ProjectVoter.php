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
        if (!in_array($attribute, [self::EDIT, self::VIEW, self::DELETE, self::CREATE])) {
            return false;
        }

        if ($attribute === self::CREATE) {
            return $subject === null;
        }

        return $subject instanceof Projects;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof Users) {
            return false;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        /**
         * @var Projects $project
         */
        $project = $subject;

        return match ($attribute) {
            self::EDIT => $this->canEdit($project, $user),
            self::VIEW => $this->canView($project, $user),
            self::DELETE => $this->canDelete($project, $user),
            self::CREATE =>$this->canCreate($user),
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

    private function canCreate(Users $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles(), true);
    }
}
