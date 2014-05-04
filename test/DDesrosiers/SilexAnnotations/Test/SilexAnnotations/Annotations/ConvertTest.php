<?php

namespace DDesrosiers\SilexAnnotations\Test\Annotations;

use DDesrosiers\SilexAnnotations\Annotations as SLX;
use DDesrosiers\SilexAnnotations\AnnotationServiceProvider;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class ConvertTest extends \PHPUnit_Framework_TestCase
{
    /** @var Application */
    protected $app;

    /** @var Client */
    protected $client;

    public function setUp()
    {
        $this->app = new Application();
        $this->app['debug'] = true;

        $this->app->register(new AnnotationServiceProvider(), array(
            "annot.srcDir" => __DIR__."/../../../../../../src",
            "annot.controllers" => array("DDesrosiers\\SilexAnnotations\\Test\\Annotations\\ConvertTestController")
        ));

        $this->client = new Client($this->app);
    }

    public function testConvert()
    {
        $this->client->request("GET", "/45");
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("50", $response->getContent());
    }
}

class ConvertTestController
{
    /**
     * @SLX\Request(method="GET", uri="/{var}")
     * @SLX\Convert(variable="var", callback="DDesrosiers\SilexAnnotations\Test\Annotations\ConvertTestController::convert")
     */
    public function testMethod($var)
    {
        return new Response($var);
    }

    public static function convert($var)
    {
        return $var+5;
    }
}