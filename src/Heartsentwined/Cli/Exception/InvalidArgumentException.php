<?php
namespace Heartsentwined\Cli\Exception;

use Heartsentwined\Cli\ExceptionInterface;

class InvalidArgumentException
    extends \InvalidArgumentException
    implements ExceptionInterface
{
}
