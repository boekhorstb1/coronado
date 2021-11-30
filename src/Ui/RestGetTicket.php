<?php

/**
 * Coronado Public UI
 *
 * This is the page where the anonymous user requests his ticket.
 */

declare(strict_types=1);

namespace Horde\Coronado\Ui;

use Horde\Coronado\Ui\RestBase;
use Horde\Coronado\TicketReserver;
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
    protected function getResponseStream(array $data)
    {
        return $this->streamFactory->createStream(json_encode($data));
    }

    protected function buildResponseStream(ServerRequestInterface $request): ?StreamInterface
    {
        $method = $request->getMethod();
        if ($method !== "POST") {
            return $this->getResponseStream(
                ['error' => 'request method needs to be post']
            );
        }
        $body = $request->getBody()->getContents();
        $data = json_decode($body, true);
        $requiredKeys = ['firstname', 'lastname', 'vacState', 'vac', 'date'];
        $vacStates = ['ungeimpft', 'erste Impfung erhalten', 'durchgeimpft'];
        $vaccines = [
            'BioNTech',
            'Moderna',
            'AstraZeneca',
            'Johnson&Johnson',
        ];
        foreach ($requiredKeys as $key) {
            if (empty($data[$key])) {
                return $this->getResponseStream(
                    ['error' => "required key $key is not set."]
                );
            }
        }
        $vacState = $data['vacState'];
        if (!in_array($vacState, $vacStates)) {
            return $this->getResponseStream(
                ['error' => "invalid vacState: $vacState"]
            );
        }
        $vac = $data['vac'];
        if (!in_array($vac, $vaccines)) {
            return $this->getResponseStream(
                ['error' => "invalid vaccine: $vac"]
            );
        }

        $ticketReserver = $this->injector->getInstance(TicketReserver::class);
        $owner = $data['firstname'] . ' ' . $data['lastname'];


        $ticket = $ticketReserver->getReserved($owner);
        if (!$ticket) {
            $ticket = $ticketReserver->reserveTicket($owner);
        }
        if ($ticket) {
            return $this->getResponseStream([
                'ticket' => [
                    'id' => $ticket->ticket_code,
                    'date' => (string) new \Horde_Date($ticket->ticket_date, 'Europe/Berlin'),
                    // TODO: should the vaccine even be decided here? Then it needs to be a field in the db.
                    // Random for now
                    'vac' => $vaccines[random_int(0, count($vaccines) - 1)],
                ],
            ]);
        } else {
            return $this->getResponseStream(
                ['error' => _('Currently no available tickets')]
            );
        }
    }
}
