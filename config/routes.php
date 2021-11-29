<?php
namespace Horde\Coronado;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

// The admin page with useful overview
$mapper->connect(
    'Admin',
    '/admin',
    [
        'controller' => Ui\AdminUi::class,
    ]
);

// The Rest Call
$mapper->connect(
    'RestGetTicket',
    '/rest/ticket',
    [
        'controller' => Ui\RestGetTicket::class,
    ]
);


// This is the default public view in Coronado
$mapper->connect(
    'Index',
    '/*path',
    [
        'controller' => Ui\PublicUi::class,
    ]
);
