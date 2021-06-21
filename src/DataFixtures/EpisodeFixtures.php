<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    private $input;

    public function __construct(Slugify $input)
    {
        $this->input = $input;
    }
    public function load(ObjectManager $manager)
    {
        $episode = new Episode();
        $episode->setTitle('Walking dead');
        $episode->setNumber('12');
        $episode->setSynopsis('Lorem ipsum dolor sit amet consectetur adipisicing elit. Maiores itaque molestiae debitis neque ipsa, accusantium quod. Ab inventore molestiae distinctio dolorum repellat perspiciatis harum ratione porro? Sunt praesentium corporis nam!');
        $episode->setSlug($this->input->generate($episode->getTitle()));
        $manager->persist($episode);
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