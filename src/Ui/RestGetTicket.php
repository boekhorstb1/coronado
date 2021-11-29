<?php
/**
 * Coronado Public UI
 *
 * This is the page where the anonymous user requests his ticket.
 */
declare(strict_types=1);
namespace Horde\Coronado\Ui;

use Horde\Coronado\Ui\RestBase;
// These are all technically not needed but may help you write code faster
use Horde\Injector\Injector;
/**
 * The standard PSR-7/PSR-15/PSR-17 fare.
 */
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Horde\Coronado\CoronadoException;
use Horde_Registry;
use Horde_Session;
use Horde\Log\Logger;




/**
 * Controller class for Public UI
 */
class RestGetTicket extends RestBase
{
    
    /**
     * Overload this method for actually implementing stuff
     * 
     * Quick & Dirty: Use $this->injector to get application services
     * Proper: Overload and amend constructor
     */
    /*
    protected function buildResponseStream(ServerRequestInterface $request): ?StreamInterface
    {
        $content = json_encode([
            'ticket' => [
                'ticket_id' => 'XYZ0XYZ1XYZ0XYZ1XYZ0XYZ1XYZ0XYZ1',
                'ticket_time' => new \Horde_Date('2021-11-24 10:30', 'UTC')
            ]
        ]);
        if ($content) {
            return $this->streamFactory->createStream($content);
        }
        throw new CoronadoException('Could not render rest output');
    }
    */
}
