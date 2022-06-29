<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');


        $button = $crawler->filter('button');
        // \dd($but);
        $this->assertEquals(1, count($button));
        $this->assertResponseIsSuccessful();

        $recipes = $crawler->filter('.card');
        //      \dd($recipes);
        $this->assertEquals(3, count($recipes));


        $this->assertSelectorTextContains('h1', 'Bienvenue sur SymRecipies');
    }
    public function testLoginButtion()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // Je sumule un clic sur bouton connecter
        $client->clickLink('Connection');

        $this->assertResponseIsSuccessful();
    }
}
