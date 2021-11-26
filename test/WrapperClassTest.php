<?php
declare(strict_types=1);
namespace Horde\Coronado\Test;
use Horde\Test\TestCase;
use Horde\Coronado\CoronadoException;
use \Coronado_Exception;
/**
 * @author     Ralf Lang <lang@b1-systems.de>
 * @license    http://www.horde.org/licenses/gpl GPL
 * @category   Horde
 * @package    Coronado
 * @subpackage UnitTests
 */
class WrapperClassTest extends TestCase
{
    public function setUp(): void
    {
    }

    public function testException()
    {
        $this->expectException(CoronadoException::class);
        throw new CoronadoException();
    }
    public function testWrappedException()
    {
        $this->expectException(CoronadoException::class);
        throw new Coronado_Exception();
    }

    public function tearDown(): void
    {
    }
}
