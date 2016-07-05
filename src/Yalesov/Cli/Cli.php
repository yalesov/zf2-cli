<?php
namespace Yalesov\Cli;

use Yalesov\ArgValidator\ArgValidator;
use Zend\Console\Console;
use Zend\Console\Adapter\AdapterInterface;

class Cli
{
    protected $console;
    protected $templates = array();

    /**
     * setConsole
     *
     * @param  Console $console
     * @return self
     */
    public function setConsole(AdapterInterface $console)
    {
        $this->console = $console;

        return $this;
    }

    /**
     * getConsole
     *
     * @return Console
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * register templates
     *
     * @param array $templates
     *  (code) => $template
     * @see self::addTemplate()
     * @return self
     */
    public function setTemplates(array $templates)
    {
        foreach ($templates as $code => $template) {
            $this->addTemplate($code, $template);
        }

        return $this;
    }

    /**
     * getTemplates
     *
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * register a template
     *
     * @param string $code     the template identifier / code
     * @param array  $template
     *  'template'  => string (optional)
     *      %s as text placeholder,
     *      not set = '%s' (plain text)
     *  'color'     => string (optional)
     *      a \Zend\Console\ColorInterface defined constant name,
     *      not set = default / normal color
     * @return self
     */
    public function addTemplate($code, array $template)
    {
        ArgValidator::assert($code, 'string');
        ArgValidator::arrayAssert($template, array(
            'template'  => array('string', 'notSet'),
            'color'     => array('string', 'notSet'),
        ));
        $this->templates[$code] = $template;

        return $this;
    }

    /**
     * output text with template
     *
     * @param  string      $text
     * @param  string      $code the template code
     * @param  bool        $echo = true, if false, will return print string instead
     * @return void|string
     */
    public function write($text, $code, $echo = true)
    {
        ArgValidator::assert($text, 'string');
        ArgValidator::assert($code, 'string');

        $templates = $this->getTemplates();
        if (!isset($templates[$code])) {
            throw new Exception\InvalidArgumentException(sprintf(
                'the template "%s" is not defined',
                $code
            ));
        }
        $template = $templates[$code];

        if (!$echo) ob_start();

        if (!$this->getConsole()) $this->initConsole();

        $console = $this->getConsole();
        if ($console && isset($template['color'])
            && defined("Zend\Console\ColorInterface::{$template['color']}")) {
            $console->setColor(
                constant("Zend\Console\ColorInterface::{$template['color']}"));
        }

        if (isset($template['template'])) {
            printf("\n{$template['template']}\n", $text);
        } else {
            echo "\n$text\n";
        }

        if ($console) $console->resetColor();
        if (!$echo) return ob_get_clean();
    }

    /**
     * try to initiate console instance if not set
     *
     * @return self
     */
    public function initConsole()
    {
        try {
            if ($console = Console::getInstance()) {
                $this->setConsole($console);
            }
        } catch (\Exception $e) {
        }

        return $this;
    }
}
