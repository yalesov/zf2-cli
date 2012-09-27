<?php
namespace Heartsentwined\Test\Cli;

use Heartsentwined\Cli\Cli;

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

        $this->cli->addTemplate('foo', array(
        ));
        $this->assertSame(array(
            'foo' => array(
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

    public function testWrite()
    {
        $blue = "\x1b[0;34m";
        $resets = "\x1b[0;49m";
        $resets .= "\x1b[22;39m";
        $resets .= "\x1b[25;39m";
        $resets .=  "\x1b[24;39m";
        $this->cli->addTemplate('foo1', array(
            'template'  => '# %s #',
            'color'     => 'BLUE',
        ));
        $this->assertSame("$blue\n# bar #\n$resets",
            $this->cli->write('bar', 'foo1', false));

        $this->cli->addTemplate('foo2', array(
            'template'  => '# %s #',
        ));
        $this->assertSame("\n# bar #\n$resets",
            $this->cli->write('bar', 'foo2', false));

        $this->cli->addTemplate('foo3', array(
            'color'     => 'BLUE',
        ));
        $this->assertSame("$blue\nbar\n$resets",
            $this->cli->write('bar', 'foo3', false));

        $this->cli->addTemplate('foo4', array(
        ));
        $this->assertSame("\nbar\n$resets",
            $this->cli->write('bar', 'foo4', false));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testNotExistTemplate()
    {
        $this->assertEmpty($this->cli->getTemplates());
        $this->cli->write('bar', 'foo', false);
    }
}
