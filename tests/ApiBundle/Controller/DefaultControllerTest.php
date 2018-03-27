<?php

namespace ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    /**
     * test
     */
    public function testIndex()
    {
        $client = static::createClient();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
