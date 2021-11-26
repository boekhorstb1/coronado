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
namespace Horde\Coronado;
use Horde_Test;
/**
 * This class provides the application configuration for the test script.
 *
 * @author    Ralf Lang <lang@b1-systems.de>
 * @category  Horde
 * @copyright 2010-2017 Horde LLC
 * @license   http://www.horde.org/licenses/gpl GPL
 * @package   Coronado
 */
class Test extends Horde_Test
{
    /**
     * The module list
     *
     * @var array
     */
    protected $_moduleList = array();

    /**
     * PHP settings list.
     *
     * @var array
     */
    protected $_settingsList = array();

    /**
     * PEAR modules list.
     *
     * @var array
     */
    protected $_pearList = array();

    /**
     * Required configuration files.
     *
     * @var array
     */
    protected $_fileList = array();

    /**
     * Inter-Horde application dependencies.
     *
     * @var array
     */
    protected $_appList = array();

    /**
     * Any application specific tests that need to be done.
     *
     * @return string  HTML output.
     */
    public function appTests(): string
    {
        return '';
    }

}
