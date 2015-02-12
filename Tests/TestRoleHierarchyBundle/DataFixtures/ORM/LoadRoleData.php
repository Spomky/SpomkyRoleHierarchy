<?php

namespace SpomkyLabs\TestRoleHierarchyBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadRoleData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $role_manager = $this->container->get('spomkylabs_role_hierarchy.role_manager');

        foreach ($this->getRoles() as $role) {
            $entity = $role_manager->createRole();
            $parent = null === $role['parent'] ? null : $this->getReference('role-'.$role['parent']);
            $entity->setName($role['name'])
                   ->setParent($parent);

            $role_manager->saveRole($entity);
            $this->addReference('role-'.$role['name'], $entity);
        }
    }

    protected function getRoles()
    {
        return array(
            array(
                "name" => "SuperAdmin",
                "parent" => null,
            ),
            array(
                "name" => "Admin",
                "parent" => "SuperAdmin",
            ),
            array(
                "name" => "Supervisor",
                "parent" => "Admin",
            ),
            array(
                "name" => "Manager",
                "parent" => "Admin",
            ),
            array(
                "name" => "Operator",
                "parent" => "Supervisor",
            ),
            array(
                "name" => "User",
                "parent" => "Manager",
            ),
        );
    }
}
