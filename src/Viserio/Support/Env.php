<?php
declare(strict_types=1);
namespace Viserio\Support;

use Closure;

class Env
{
    /**
     * Gets the value of an environment variable. Supports boolean, empty, null and base64 prefix.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default instanceof Closure ? $default() : $default;
        }

        if (preg_match('/base64:|\'base64:|"base64:/s', $value)) {
            return base64_decode(substr($value, 7));
        }

        if (in_array(
            strtolower($value),
            [
                'false',
                '(false)',
                'true',
                '(true)',
                'yes',
                '(yes)',
                'no',
                '(no)',
                'on',
                '(on)',
                'off',
                '(off)',
            ]
        )) {
            $value = str_replace(['(', ')'], '', $value);

            return filter_var(
                $value,
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );
        } elseif ($value === 'null' || $value === '(null)') {
            return null;
        } elseif (is_numeric($value)) {
            return $value + 0;
        } elseif ($value === 'empty' || $value === '(empty)') {
            return '';
        }

        if (strlen($value) > 1 &&
            mb_substr($value, 0, strlen('"')) === '"' &&
            mb_substr($value, -strlen('"')) === '"'
        ) {
            return mb_substr($value, 1, -1);
        }

        return $value;
    }
}
