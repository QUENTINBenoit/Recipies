<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactTest extends WebTestCase
{
    public function testFormContact(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Formulaire de contact');

        // Récupérer le formulaire
        $submitButton = $crawler->selectButton('Soumettre ma demande');
        $form = $submitButton->form();

        $form["contact[fullName]"] = "Marcus Maxime";
        $form["contact[email]"] = "mex@mcd.com";
        $form["contact[subject]"] = "Test";
        $form["contact[message]"] = "Test du test ";
        //\dd($form);
        // Soumettre le formulaire
        $client->submit($form);
        // Vérifier le status HTTP
        // \dd($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Vérifier l'envoie du mail
        $this->assertEmailCount(0);
        // $client->followRedirect(301);

        // Vérifier la présence du message de succès
        $this->assertSelectorTextContains(
            'div.alert',
            'Votre demande a été envoyé avec succès !'
        );
    }
}
