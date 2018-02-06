<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiUserControllerTest extends WebTestCase
{
    public function testGetuser()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/getUser');
    }

}
