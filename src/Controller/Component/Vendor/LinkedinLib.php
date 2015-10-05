<?php
namespace User\Controller\Component\Vendor;

use Happyr\LinkedIn\LinkedIn;

class LinkedinLib extends LinkedIn
{
    /**
     * Constructor.
     *
     * @param string $appId
     * @param string $appSecret
     * @param string $format    'json', 'xml' or 'simple_xml'
     */
    public function __construct($appId, $appSecret, $format = 'json')
    {
        Parent::__construct($appId, $appSecret, $format = 'json');
    }
    
    public function linkedinget($resource, $token)
    {
        $linkedIn = new LinkedIn('77y82deosa7au1', 'RkTgREyE7ysqyl6d');
        $method = 'GET';
        $options = [];

        // Add access token to the headers
        $options['headers']['Authorization'] = sprintf('Bearer %s', $token);
        // Do logic and adjustments to the options
        $linkedIn->filterRequestOption($options);

        // Generate an url
        $url = $linkedIn->getUrlGenerator()->getUrl('api', $resource, isset($options['query']) ? $options['query'] : []);
        unset($options['query']);
        // $method that url
        $result = $linkedIn->getRequest()->send($method, $url, $options);

        return $result;
    }
}
