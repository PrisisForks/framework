<?php
declare(strict_types=1);
namespace Viserio\Component\Contract\Http\Exception;

use UnexpectedValueException as BaseUnexpectedValueException;

class UnexpectedValueException extends BaseUnexpectedValueException implements Exception
{
}