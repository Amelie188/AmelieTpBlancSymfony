<?php

namespace App\DataFixtures;

use App\Entity\Employe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EmployeFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $employe = new Employe();
        $employe->setSecteur('Direction');
        $employe->setNom('admin');
        $employe->setPrenom('admin');
        $employe->setEmail('admin@deloitte.com');
        $employe->setPhoto('images/profil.png');
        $employe->setRoles(['ROLE_ADMIN']);

        $password = $this->encoder->encodePassword($employe, 'admin123@');
        $employe->setPassword($password);
    
        $manager->persist($employe);
        $manager->flush();



        $employe = new Employe();
        $employe->setSecteur('Comptabilité');
        $employe->setNom('Compta');
        $employe->setPrenom('Compta');
        $employe->setEmail('Compta@deloitte.com');
        $employe->setPhoto('images/profil.png');
        $employe->setRoles(['ROLE_COMPTA']);

        $password = $this->encoder->encodePassword($employe, 'compta123@');
        $employe->setPassword($password);
    
        $manager->persist($employe);
        $manager->flush();

        $employe = new Employe();
        $employe->setSecteur('Secrétaire');
        $employe->setNom('Secretaire');
        $employe->setPrenom('Secretaire');
        $employe->setEmail('Secretaire@deloitte.com');
        $employe->setPhoto('images/profil.png');
        $employe->setRoles(['ROLE_SECRETAIRE']);

        $password = $this->encoder->encodePassword($employe, 'secretaire123@');
        $employe->setPassword($password);
    
        $manager->persist($employe);
        $manager->flush();
    }
}

?>