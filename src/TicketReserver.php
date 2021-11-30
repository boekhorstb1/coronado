<?php

declare(strict_types=1);

namespace Horde\Coronado;

use Horde_Date;
use Horde_Db_Adapter;
use Horde\Coronado\Model\TicketRepo;
use Horde\Coronado\Model\Ticket;

class TicketReserver
{
    protected Horde_Db_Adapter $dba;
    protected TicketRepo $ticketRepo;

    public function __construct(
        Horde_Db_Adapter $dba,
        TicketRepo $ticketRepo
    ) {
        $this->dba = $dba;
        $this->ticketRepo = $ticketRepo;
    }

    public function getReserved($owner): ?Ticket
    {
        $ts = time();
        $tickets = $this->ticketRepo->getByOwner($owner);

        if (!$tickets) {
            return null;
        }
        usort($tickets, function ($a, $b) {
            return $b->ticket_date - $a->ticket_date;
        });
        $ticket = $tickets[0];
        if ($ticket->ticket_date < $ts) {
            return null;
        }
        return $ticket;
    }

    public function reserveTicket($owner): ?Ticket
    {
        $earliest = (new Horde_Date())->add(['hour' => 4]);
        $sql = 'UPDATE coronado_tickets SET ticket_owner=? WHERE ticket_owner="" AND ticket_date > ? LIMIT 1';
        $result = $this->dba->update($sql, [$owner, $earliest]);
        if ($result > 0) {
            return $this->getReserved($owner);
        }
        return null;
    }
}
