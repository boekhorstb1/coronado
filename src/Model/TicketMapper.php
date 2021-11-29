<?php
namespace Horde\Coronado\Model;
use Horde\Rdo\BaseMapper;
use Horde\Rdo\Base;

/**
 * ORM Mapper for the Ticket.
 * 
 * Quick designs can just use the mapper as the base class for Repository use cases
 * Clean designs would hide the mapper behind separate Creator and Repository or ReadRepository/WriteRepository classes
 */
class TicketMapper extends BaseMapper
{
    protected $_classname = Ticket::class;
    protected $_table = 'coronado_tickets';

}