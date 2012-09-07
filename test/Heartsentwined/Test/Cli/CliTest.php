<?php
namespace Heartsentwined\Test\Cli;

use Heartsentwined\Cli\Cli;
use Heartsentwined\Cli\Exception;

class CliTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->cli = new Cli;
    }

    public function tearDown()
    {
    }

    public function testAddTemplate()
    {
        $this->cli->addTemplate('foo', array(
            'template'  => '# %s #',
            'color'     => 'BLUE',
        ));
        $this->assertSame(array(
            'foo' => array(
                'template'  => '# %s #',
                'color'     => 'BLUE',
            )),
            $this->cli->getTemplates());

        $this->cli->addTemplate('bar', array(
            'color'     => 'BLUE',
        ));
        $this->assertSame(array(
            'foo' => array(
                'template'  => '# %s #',
                'color'     => 'BLUE',
            ),
            'bar' => array(
                'color'     => 'BLUE',
            )),
            $this->cli->getTemplates());

        $this->cli->addTemplate('baz', array(
            'template'  => '# %s #',
        ));
        $this->assertSame(array(
            'foo' => array(
                'template'  => '# %s #',
                'color'     => 'BLUE',
            ),
            'bar' => array(
                'color'     => 'BLUE',
            ),
            'baz' => array(
                'template'  => '# %s #',
            )),
            $this->cli->getTemplates());

        $this->cli->addTemplate('qux', array(
        ));
        $this->assertSame(array(
            'foo' => array(
                'template'  => '# %s #',
                'color'     => 'BLUE',
            ),
            'bar' => array(
                'color'     => 'BLUE',
            ),
            'baz' => array(
                'template'  => '# %s #',
            ),
            'qux' => array(
            )),
            $this->cli->getTemplates());
    }

    public function testInitConsole()
    {
        $this->cli->initConsole();
        $this->assertNotEmpty($this->cli->getConsole());
    }
}
