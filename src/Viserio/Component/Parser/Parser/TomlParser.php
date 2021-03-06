<?php

declare(strict_types=1);

/**
 * This file is part of Narrowspark Framework.
 *
 * (c) Daniel Bannert <d.bannert@anolilab.de>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Viserio\Component\Parser\Parser;

use Viserio\Contract\Parser\Exception\ParseException;
use Viserio\Contract\Parser\Parser as ParserContract;
use Viserio\Contract\Support\Exception\MissingPackageException;
use Yosymfony\Toml\Exception\ParseException as TomlParseException;
use Yosymfony\Toml\Toml as YosymfonyToml;

class TomlParser implements ParserContract
{
    /**
     * Create a new Toml parser.
     *
     * @throws \Viserio\Contract\Support\Exception\MissingPackageException
     *
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        if (! \class_exists(YosymfonyToml::class)) {
            throw new MissingPackageException(['yosymfony/toml'], self::class);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function parse(string $payload): array
    {
        try {
            return YosymfonyToml::parse($payload);
        } catch (TomlParseException $exception) {
            throw ParseException::createFromException('Unable to parse the TOML string.', $exception);
        }
    }
}
