<?php

namespace Api\Test\Functional;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

class WebTestCase extends TestCase
{
    protected function get(string $uri): ResponseInterface
    {
        return $this->method($uri, 'GET');
    }

    protected function method(string $uri, string $method): ResponseInterface
    {
        /** @var ServerRequestInterface $request */
        $request = (new ServerRequest())
            ->withUri(new Uri('http://localhost:8081' . $uri))
            ->withMethod($method);

        return $this->request($request);
    }

    protected function request(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->app()->process($request, new Response());
        $response->getBody()->rewind();

        return $response;
    }

    protected function app(): App
    {
        $app = new App($this->container());
        (require_once 'config/routes.php')($app);

        return $app;
    }

    protected function loadFixtures(array $fixtures): void
    {
        $container = $this->container();
        $em = $container->get(EntityManagerInterface::class);

        $loader = new Loader();
        foreach ($fixtures as $class) {
            if ($container->has($class)) {
                $fixture = $container->get($class);
            } else {
                $fixture = new $class;
            }

            $loader->addFixture($fixture);
        }

        $executer = new ORMExecutor($em, new ORMPurger($em));
        $executer->execute($loader->getFixtures());
    }

    protected function container(): ContainerInterface
    {
        return require_once 'config/container.php';
    }
}