<?php
declare(strict_types=1);
namespace Viserio\Translation\PluralCategorys;

use Viserio\Contracts\Translation\PluralCategory as CategoryContract;
use Viserio\Translation\Traits\NormalizeIntegerValueTrait;

class Gaelic implements CategoryContract
{
    use NormalizeIntegerValueTrait;

    /**
     * Returns category key by count.
     *
     * Locales: gd
     *
     * Languages:
     * - Scottish Gaelic (gd)
     *
     * Rules:
     *  one → n in 1,11;
     *  two → n in 2,12;
     *  few → n in 3..10,13..19;
     *  other → everything else
     *
     * @param int|string $count
     *
     * @return int
     */
    public function category($count): int
    {
        $count = $this->normalizeInteger($count);

        if ($count === 1 || $count === 11) {
            return 0;
        } elseif ($count === 2 || $count === 12) {
            return 1;
        } elseif (!is_float($count) &&
            (
                ($count >= 3 && $count <= 10) ||
                ($count >= 13 && $count <= 19)
            )
        ) {
            return 2;
        }

        return 3;
    }
}
