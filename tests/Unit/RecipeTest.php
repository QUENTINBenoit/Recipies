<?php

namespace App\Tests\Unit;

use App\Entity\Mark;
use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipeTest extends KernelTestCase
{
    public function getEntity(): Recipe
    {
        return (new Recipe())
            ->setName('Recipe #1')
            ->setDescription('Description')
            ->setIsFavorite('true')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
    }



    public function testEntityIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $recipe = $this->getEntity();
        $errors = $container->get('validator')->validate($recipe);

        $this->assertCount(0, $errors);
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }
    public function testInvalidName()
    {
        self::bootKernel();
        $container = static::getContainer();


        $recipe = $this->getEntity();
        $recipe->setName('');
        $errors = $container->get('validator')->validate($recipe);
        $this->assertCount(1, $errors);
    }

    public function testGetAverage()
    {
        $recipe = $this->getEntity();
        $user = static::getContainer()->get('doctrine.orm.entity_manager')->find(User::class, 13);

        for ($i = 0; $i < 5; $i++) {
            $mark = new Mark();
            $mark->setMark(2)
                ->setUser($user)
                ->setRecipe($recipe);
        }
        $recipe->addMark($mark);

        $this->assertTrue(2.0 === $recipe->getAverage());
    }
}
