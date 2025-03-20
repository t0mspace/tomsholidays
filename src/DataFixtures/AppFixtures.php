<?php

namespace App\DataFixtures;

use App\Entity\Department;
use App\Entity\Employee;
use App\Entity\PublicHoliday;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ITdepartment = new Department();
        $ITdepartment->setName('IT');
        $HRdepartment = new Department();
        $HRdepartment->setName('HR');
        $einstein = new Employee();
        $curie = new Employee();
        $employes = new ArrayCollection();

        $manager->persist($ITdepartment);
        $manager->persist($HRdepartment);

        $einstein->setDepartment($ITdepartment)
            ->setEmail("albert_einstein@yopmail.com")
            ->setFirstname('Albert')
            ->setLastname('Einstein')
            ->setRoles('[ROLE_MANAGER]')
            ->setNbrOfLegalVacationDaysRemaining(30)
            ->setPassword('azerty');

        $employes->add($einstein);

        $curie
            ->setDepartment($HRdepartment)
            ->setEmail("marie_curie@yopmail.com")
            ->setFirstname('Marie')
            ->setLastname('Curie')
            ->setRoles('[ROLE_MANAGER]')
            ->setNbrOfLegalVacationDaysRemaining(30)
            ->setPassword('azerty');
        $employes->add($curie);

        $trump = new Employee();
        $trump->setDepartment($HRdepartment)
            ->setEmail("donald_trump@yopmail.com")
            ->setFirstname("Donald")
            ->setLastname("Trump")
            ->setRoles('[ROLE_EMPLOYEE]')
            ->setNbrOfLegalVacationDaysRemaining(30)
            ->setPassword('azerty');
        $employes->add($trump);

        $obama = new Employee();
        $obama->setDepartment($HRdepartment)
            ->setEmail("barack_obama@yopmail.com")
            ->setFirstname("Barack")
            ->setLastname("Obama")
            ->setRoles('[ROLE_EMPLOYEE]')
            ->setNbrOfLegalVacationDaysRemaining(30)
            ->setPassword('azerty');
        $employes->add($obama);

        $dion = new Employee();
        $dion->setDepartment($HRdepartment)
            ->setEmail("celine_dion@yopmail.com")
            ->setFirstname("Celine")
            ->setLastname("Dion")
            ->setRoles('[ROLE_EMPLOYEE]')
            ->setNbrOfLegalVacationDaysRemaining(30)
            ->setPassword('azerty');
        $employes->add($dion);

        foreach ($employes as $employee) {
            $manager->persist($employee);
        }

        $pubblicHollidays = new ArrayCollection();
        $lundiPaques = new PublicHoliday();

        $lundiPaques->setDate(new \DateTimeImmutable("2025-04-21"));
        $lundiPaques->setName("Paques");
        $pubblicHollidays->add($lundiPaques);
        $premierMai = (new PublicHoliday())
            ->setName("Fête du travail");
        $premierMai->setDate(new \DateTimeImmutable("2025-05-01"));

        $pubblicHollidays->add($premierMai);

        $ascension = new PublicHoliday();
        $ascension->setName("Ascension");
        $ascension->setDate(new \DateTimeImmutable("2025-05-29"));

        $pubblicHollidays->add($ascension);

        $pentcote = new PublicHoliday();
        $pentcote->setName("Pentecote");
        $pentcote->setDate(new \DateTimeImmutable("2025-06-09"));

        $pubblicHollidays->add($pentcote);

        $feteNational = new PublicHoliday();
        $feteNational->setName("Fete Nationale Belge");
        $feteNational->setDate(new \DateTimeImmutable("2025-07-21"));

        $pubblicHollidays->add($feteNational);

        $assemtion = new PublicHoliday();
        $assemtion->setName("Assomption");
        $assemtion->setDate(new \DateTimeImmutable("2025-08-15"));

        $pubblicHollidays->add($assemtion);

        $toussain = new PublicHoliday();
        $toussain->setName("Toussaint");
        $toussain->setDate(new \DateTimeImmutable("2025-11-01"));

        $pubblicHollidays->add($toussain);

        $armistice = new PublicHoliday();
        $armistice->setName("Armistice");
        $armistice->setDate(new \DateTimeImmutable("2025-11-11"));

        $pubblicHollidays->add($armistice);

        $noel = new PublicHoliday();
        $noel->setName("Noel");
        $noel->setDate(new \DateTimeImmutable("2025-12-25"));

        $pubblicHollidays->add($noel);

        foreach ($pubblicHollidays as $pubblicHolliday) {
            $manager->persist($pubblicHolliday);
        }

        $manager->flush();
    }
}
