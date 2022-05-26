<?php

namespace App;

use App\Controllers\AnswerController;
use App\Controllers\ControllerInterface;
use App\Controllers\SessionController;
use App\Controllers\SessionStorageController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Application
{
    public function __construct()
    {
    }

    public function run(): void
    {
        $routes = new RouteCollection();
        $this->routeCollection($routes);

        $context = new RequestContext();
        $context->fromRequest(Request::createFromGlobals());

        $matcher = new UrlMatcher($routes, $context);
        try {
            $parameters = $matcher->match($context->getPathInfo());
        } catch (NoConfigurationException) {
            return;
        } catch (ResourceNotFoundException) {
            return;
        } catch (MethodNotAllowedException) {
            return;
        }

        $options = [];
        foreach ($parameters as $key => $value) {
            if (substr($key, 0, 3) === 'app') {
                $options[$key] = $value;
            }
        }

        $controllerName = $parameters['_controller'];
        /** @var ControllerInterface $controller */
        $controller = new $controllerName();
        $controller->handle($options);
    }

    private function routeCollection(RouteCollection $routeCollection): void
    {
        $routeCollection->add(
            'session-storage-new',
            new Route('/session-storage', ['_controller' => SessionStorageController::class])
        );
        $routeCollection->add(
            'session-storage-new-',
            new Route('/session-storage/', ['_controller' => SessionStorageController::class])
        );

        $routeCollection->add(
            'session-storage-new-or-saved',
            new Route(
                path: '/session-storage/{appSessionId}',
                defaults: ['_controller' => SessionStorageController::class],
                methods: [Request::METHOD_GET]
            ),
        );

        $routeCollection->add(
            'session-new-or-saved',
            new Route(
                path: '/session',
                defaults: ['_controller' => SessionController::class],
                methods: [Request::METHOD_GET]
            ),
        );
        $routeCollection->add(
            'session-new-or-saved-',
            new Route(
                path: '/session/',
                defaults: ['_controller' => SessionController::class],
                methods: [Request::METHOD_GET]
            ),
        );
        $routeCollection->add(
            'session-new-or-saved-id',
            new Route(
                path: '/session/{appSessionId}',
                defaults: ['_controller' => SessionController::class],
                methods: [Request::METHOD_GET]
            ),
        );

        $routeCollection->add(
            'answer',
            new Route(
                path: '/answer/{appSessionId}/{appQuestionId}/{appAnswerId}',
                defaults: ['_controller' => AnswerController::class],
                methods: [Request::METHOD_GET]
            ),
        );
    }
}
