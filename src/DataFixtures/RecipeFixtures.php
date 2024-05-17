<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Ingredient;
use App\Entity\Quantity;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class RecipeFixtures
 *
 * @author Trésor-ILUNGA <hello@tresor-ilunga.tech>
 */
class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly SluggerInterface $slugger)
    {}

    public function load(ObjectManager $manager): void
    {
        $faker =  Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));

        $ingredients = array_map(fn(string $name) => (new Ingredient())
            ->setName($name)
            ->setSlug(strtolower((string)$this->slugger->slug($name))), [
            'Farine',
            'Sucre',
            'Oeufs',
            'Beurre',
            'Lait',
            'Lait',
            'Levure chimique',
            'Sel',
            'Chocolat noir',
            'Pépites de chocolat',
            'Fruits secs (amandes, noix, etc.)',
            'Vanille',
            'Cannelle',
            'Fraise',
            'Banane',
            'Pomme',
            'Carotte',
            'Oignon',
            'Ail',
            'Échalote',
            'Herbes fraîches (ciboulette, persil, etc.)',
            'Oranges',
        ]);

        $units = [
          "g",
          "kg",
          "L",
          "ml",
          "cl",
          "dL",
          "c. à soupe",
          "c. à café",
          "pincé",
          "verre"
        ];

        foreach ($ingredients as $ingredient)
        {
            $manager->persist($ingredient);
        }

        $categories = ['Plat chaud', 'Dessert', 'Entrée', 'Goûter'];

        foreach ($categories as $c)
        {
            $category = (new Category())
                ->setName($c)
                ->setSlug((string)$this->slugger->slug($c));

            $manager->persist($category);
            $this->addReference($c, $category);
        }

        for ($i = 0; $i < 20; $i++)
        {
            $title = $faker->foodName();
            $recipe = (new Recipe())
                ->setTitle($title)
                ->setSlug((string)$this->slugger->slug($title))
                ->setContent($faker->paragraphs(10, true))
                ->setCategory($this->getReference($faker->randomElement($categories)))
                ->setUser($this->getReference('USER' . $faker->numberBetween(1, 10)))
                ->setDuration($faker->numberBetween(2, 60));


            foreach ($faker->randomElements($ingredients, $faker->numberBetween(2,5)) as $ingredient)
            {
                $recipe->addQuantity((new Quantity())
                    ->setQuantity($faker->numberBetween(1, 250))
                    ->setUnit($faker->randomElement($units))
                    ->setIngredient($ingredient)
                );

            }
            $manager->persist($recipe);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
