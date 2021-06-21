<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{

    const PROGRAMS = [
        'Walking Dead',
        'The Handmaid\'s Tale',
        'The Big Bang Theory',
        'Scrubs',
        'American Horror Story',
    ];

    private $input;

    public function __construct(Slugify $input)
    {
        $this->input = $input;
    }

    public function load(ObjectManager $manager)
    {
        $program = new Program();
        $program->setTitle('Walking dead');
        $program->setSummary('Des zombies envahissent la terre');
        $program->setCountry('USA');
        $program->setYear('2015');
        $program->setCategory($this->getReference('category_0'));
        $program->setSlug($this->input->generate($program->getTitle()));
        //ici les acteurs sont insérés via une boucle pour être DRY mais ce n'est pas obligatoire
        for ($i=0; $i < count(ActorFixtures::ACTORS); $i++) {
            $program->addActor($this->getReference('actor_' . $i));
        }
        $manager->persist($program);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          ActorFixtures::class,
          CategoryFixtures::class,
        ];
    }

}
