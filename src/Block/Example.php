<?php
/**
 * Copyright 2021 B1 Systems GmbH (https://www.b1-systems.de)
 *
 * See the enclosed file LICENSE for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 *
 * @author   Ralf Lang <lang@b1-systems.de>
 * @category Horde
 * @license  http://www.horde.org/licenses/gpl GPL
 * @package  Coronado
 */
declare(strict_types=1);
namespace Horde\Coronado\Block;
use \Horde_Core_Block;

/**
 * Coronado Block example.
 *
 * @author    Ralf Lang <lang@b1-systems.de>
 * @category  Horde
 * @copyright 2013-2017 Horde LLC
 * @license   http://www.horde.org/licenses/gpl GPL
 * @package   Coronado
 */
class Example extends Horde_Core_Block
{
    /**
     */
    public function __construct($app, $params = array())
    {
        parent::__construct($app, $params);

        $this->_name = _("Example Block");
    }

    /**
     */
    protected function _params()
    {
        return array(
            'color' => array(
                'type' => 'text',
                'name' => _("Color"),
                'default' => '#ff0000'
            )
        );
    }

    /**
     */
    protected function _title()
    {
        return _("Color");
    }

    /**
     */
    protected function _content()
    {
        $html  = '<table width="100" height="100" bgcolor="%s">';
        $html .= '<tr><td>&nbsp;</td></tr>';
        $html .= '</table>';

        return sprintf($html, $this->_params['color']);
    }

}
