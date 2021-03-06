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

namespace Viserio\Provider\Framework\Container\Processor;

use JsonException;
use Viserio\Component\Container\Processor\AbstractParameterProcessor;
use Viserio\Contract\Container\Exception\InvalidArgumentException;
use Viserio\Contract\Container\Exception\RuntimeException;

class ComposerExtraProcessor extends AbstractParameterProcessor
{
    /**
     * Path to the composer.json.
     *
     * @var string
     */
    private $composerJsonPath;

    /**
     * Create a new ComposerExtraProcessor instance.
     *
     * @param string $dirPath
     * @param string $composerJsonName
     */
    public function __construct(string $dirPath, string $composerJsonName = 'composer.json')
    {
        $this->composerJsonPath = \rtrim($dirPath, '/\\') . \DIRECTORY_SEPARATOR . $composerJsonName;
    }

    /**
     * {@inheritdoc}
     */
    public static function isRuntime(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public static function getProvidedTypes(): array
    {
        return ['composer-extra' => 'string'];
    }

    /**
     * {@inheritdoc}
     */
    public function process(string $parameter)
    {
        if (! file_exists($this->composerJsonPath)) {
            throw new RuntimeException(\sprintf('File [%s] not found (resolved from [%s]).', $this->composerJsonPath, $parameter));
        }

        try {
            $json = \json_decode(\trim((string) \file_get_contents($this->composerJsonPath)), true, 512, \JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException(\sprintf('%s in [%s] file.', $exception->getMessage(), $this->composerJsonPath), $exception->getCode(), $exception);
        }

        [$key,, $search] = $this->getData($parameter);

        $value = $json['extra'][$key] ?? null;

        if ($value === null) {
            throw new InvalidArgumentException(\sprintf('Composer extra config for [%s] was not found.', $parameter));
        }

        return \str_replace($search, $value, $parameter);
    }
}
