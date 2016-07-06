<?php
namespace Yalesov\Cli\Exception;

use Yalesov\Cli\ExceptionInterface;

class InvalidArgumentException
  extends \InvalidArgumentException
  implements ExceptionInterface
{
}
