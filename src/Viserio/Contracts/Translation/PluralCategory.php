<?php
declare(strict_types=1);
namespace Viserio\Contracts\Translation;

interface PluralCategory
{
    /**
     * Returns category key by count.
     *
     * @param int|string $count
     *
     * @return int
     */
    public function category($count): int;
}