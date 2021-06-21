<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $season = new Season();
        $season->setYear('2015');
        $season->setNumber('12');
        $season->setDescription('Vive le vent');
        $manager->persist($season);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          ActorFixtures::class,
          CategoryFixtures::class,
          ProgramFixtures::class,
        ];
    }

}