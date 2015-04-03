<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrdersControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/zamowienia');
    }

    public function testEdit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/zamowienia/edytuj/{id}');
    }

    public function testRemove()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/zamowienia/usun/{id}');
    }

    public function testRealise()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/zamowienia/realizuj/{id}');
    }

}
