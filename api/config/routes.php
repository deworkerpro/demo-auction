<?php

declare(strict_types=1);

use App\Http\Action;
use App\Router\StaticRouteGroup as Group;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return static function (App $app): void {
    $app->get('/', Action\HomeAction::class);

    $app->map(['GET', 'POST'], '/authorize', Action\AuthorizeAction::class);
    $app->post('/token', Action\TokenAction::class);

    $app->group('/v1', new Group(static function (RouteCollectorProxy $group): void {
        $group->group('/auth', new Group(static function (RouteCollectorProxy $group): void {
            $group->post('/join', Action\V1\Auth\Join\RequestAction::class);
            $group->post('/join/confirm', Action\V1\Auth\Join\ConfirmAction::class);

            $group->get('/user', Action\V1\Auth\UserAction::class);
        }));

        $group->group('/newsletter', new Group(static function (RouteCollectorProxy $group): void {
            $group->get('', Action\V1\Newsletter\ShowAction::class);
            $group->post('/subscribe', Action\V1\Newsletter\SubscribeAction::class);
            $group->post('/unsubscribe', Action\V1\Newsletter\UnsubscribeAction::class);
        }));
    }));
};
