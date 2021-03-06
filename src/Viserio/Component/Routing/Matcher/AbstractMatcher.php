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

namespace Viserio\Component\Routing\Matcher;

use Viserio\Contract\Routing\Exception\InvalidArgumentException;
use Viserio\Contract\Routing\SegmentMatcher as SegmentMatcherContract;

abstract class AbstractMatcher implements SegmentMatcherContract
{
    /**
     * Stores all parameters keys.
     *
     * @var array
     */
    protected $parameterKeys = [];

    /**
     * {@inheritdoc}
     */
    public function getParameterKeys(): array
    {
        return $this->parameterKeys;
    }

    /**
     * {@inheritdoc}
     */
    public function getMatchedParameterExpressions(string $segmentVariable, ?int $uniqueKey = null): array
    {
        return \array_fill_keys($this->parameterKeys, $segmentVariable);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Viserio\Contract\Routing\Exception\InvalidArgumentException
     */
    public function mergeParameterKeys(SegmentMatcherContract $matcher): void
    {
        if ($matcher->getHash() !== $this->getHash()) {
            throw new InvalidArgumentException(\sprintf('Cannot merge parameters: Matcher\'s must be equivalent, [%s] expected, [%s] given.', $matcher->getHash(), $this->getHash()));
        }

        $this->parameterKeys = \array_unique(
            \array_merge($this->parameterKeys, $matcher->getParameterKeys()),
            \SORT_NUMERIC
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getHash(): string
    {
        return \get_class($this) . ':' . $this->getMatchHash();
    }

    /**
     * Returns a unique hash for the matching criteria of the segment.
     *
     * @return string
     */
    abstract protected function getMatchHash(): string;
}
