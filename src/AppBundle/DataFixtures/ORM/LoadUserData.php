<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('pl_PL');
        
        $userAdmin = new User;
        $userAdmin->setUsername('admin');
        $userAdmin->setPlainPassword('admin');
        $userAdmin->setRoles(array('ROLE_ADMIN'));
        $userAdmin->setEnabled(true);
        $userAdmin->setEmail($faker->email);
        $this->addReference('user'. 11, $userAdmin);
//        $userAdmin->setRoles(['ROLE_ADMIN']);
        $manager->persist($userAdmin);
        
        $userUser = new User;
        $userUser->setUsername('user');
        $userUser->setPlainPassword('user');
        $userUser->setEmail($faker->email);
        $userUser->setEnabled(true);
        $this->addReference('user'. 10, $userUser);
        $manager->persist($userUser);
        
        for($j=0 ; $j < 10 ; $j++){
            $user = new User;
            $user->setUsername($faker->userName);
            $user->setPlainPassword($faker->word);
            $user->setEmail($faker->email);
            $userUser->setEnabled(true);
            $this->addReference('user'. $j, $user);
            $manager->persist($user);
            
        }
        
        $manager->flush();
    }
    
    public function getOrder()
    {
        return 1;
    }
}
