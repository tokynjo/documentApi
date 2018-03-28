<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * Test
     */
    public function testIndex()
    {
        $client = static::createClient();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
