<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // generate 50 fake french tags
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $tag = new Tag();
            $tag->setName($faker->word);
            $manager->persist($tag);
        }
        $manager->flush();
    }
}
