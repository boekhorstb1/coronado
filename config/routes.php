<?php
namespace Horde\Coronado;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

// The former canonical name of that route
$mapper->connect(
    'Admin',
    '/admin',
    [
        'controller' => Ui\Admin::class,
    ]
);

// This is the default public view in Coronado
$mapper->connect(
    'Index',
    '/*path',
    [
        'controller' => Ui\Public::class,
    ]
);
