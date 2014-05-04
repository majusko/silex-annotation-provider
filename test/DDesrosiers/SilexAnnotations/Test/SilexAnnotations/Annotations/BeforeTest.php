<?php

namespace DDesrosiers\SilexAnnotations\Test\Annotations;

use DDesrosiers\SilexAnnotations\Annotations as SLX;
use DDesrosiers\SilexAnnotations\AnnotationServiceProvider;
use Exception;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;

class BeforeTest extends \PHPUnit_Framework_TestCase
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
            "annot.controllers" => array("DDesrosiers\\SilexAnnotations\\Test\\Annotations\\BeforeTestController")
        ));

        $this->client = new Client($this->app);
    }

    public function testBefore()
    {
        $this->client->request("GET", "/test");
        $response = $this->client->getResponse();
        $this->assertEquals(500, $response->getStatusCode());
    }
}

class BeforeTestController
{
    /**
     * @SLX\Request(method="GET", uri="/test")
     * @SLX\Before("DDesrosiers\SilexAnnotations\Test\Annotations\BeforeTestController::beforeCallback")
     */
    public function testMethod($var)
    {
        return new Response($var);
    }

    public static function beforeCallback()
    {
        throw new Exception("before callback");
    }
}