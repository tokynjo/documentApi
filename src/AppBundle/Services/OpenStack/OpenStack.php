<?php
namespace AppBundle\Services\OpenStack;
use AppBundle\Manager\FileManager;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use OpenStack\Common\Transport\Utils as TransportUtils;
use OpenStack\Identity\v2\Service;
use OpenStack\OpenStack as Os;

class OpenStack
{

    protected $openStack = null;
    protected $options = [];
    protected $token = null;

    public function __construct($os_options = [])
    {

        if(isset($os_options['auth_url'])) {
            $this->options['authUrl'] = $os_options['auth_url'];
        }
        if(isset($os_options['region_name'])) {
            $this->options['region'] = $os_options['region_name'];
        }
        if(isset($os_options['username'])) {
            $this->options['username'] = $os_options['username'];
        }
        if(isset($os_options['password'])) {
            $this->options['password'] = $os_options['password'];
        }
        if(isset($os_options['tenant_id'])) {
            $this->options['tenantId'] = $os_options['tenant_id'];
        }
        if(isset($os_options['tenant_name'])) {
            $this->options['tenantName'] = $os_options['tenant_name'];
        }
        //$this->options['verify'] = $os_options['verify'];


        $httpClient = new Client([
            'base_uri' => TransportUtils::normalizeUrl($os_options['auth_url']),
            'handler'  => HandlerStack::create(),
        ]);

        $this->options['identityService'] = Service::factory($httpClient);
        $this->openStack = new Os($this->options);

        return $this;
    }

    /**
     * @return mixed
     */
    public function generateToken()
    {
        $this->token =  $this->openStack->identityV2()->generateToken($this->options);

        return $this;
    }
}
