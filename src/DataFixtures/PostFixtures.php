<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PostFixtures extends Fixture  implements DependentFixtureInterface
{
    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(Category::class)->findAll();
        $tags = $manager->getRepository(Tag::class)->findAll();
        // generate 150 french posts with random category and random tags
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 150; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence(5));
            $post->setContent($faker->paragraph(5));
            // set random isPublished value
            $post->setIsPublished(rand(0, 1));
            // set random publishedAt value DateTimeImmutable
            $post->setcreatedAt(new \DateTimeImmutable($faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s')));
            $post->setImage('https://picsum.photos/id/'.rand(1, 1000).'/200/300');
            $random_categories_number = rand(1, 3);
            $random_tags_number = rand(1, 3);
            for ($j = 0; $j < $random_categories_number; $j++) {
                $post->addCategory($faker->randomElement($categories));
            }
            for ($j = 0; $j < $random_tags_number; $j++) {
                $post->addTag($faker->randomElement($tags));
            }

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            TagFixtures::class,
        ];
    }
}
