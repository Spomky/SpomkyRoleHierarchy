<?php

namespace SpomkyLabs\TestRoleHierarchyBundle\Util;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use SpomkyLabs\TestRoleHierarchyBundle\Entity\User;
use SpomkyLabs\TestRoleHierarchyBundle\Entity\UserManager;

class UserProvider implements UserProviderInterface
{
    private $user_manager;

    public function __construct(UserManager $user_manager)
    {
        $this->user_manager = $user_manager;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->user_manager->getUser($username);

        if ($user) {
            return $user;
        }

        throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'SpomkyLabs\TestRoleHierarchyBundle\Entity\User';
    }
}