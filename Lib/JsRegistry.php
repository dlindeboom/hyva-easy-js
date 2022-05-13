<?php

/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

declare(strict_types=1);

namespace Hyva\EasyJs\Lib;

class JsRegistry
{
    static array $codes = [];

    private int $code;

    public function __construct()
    {
        $this->code = self::getRandomCode();
    }

    private static function getRandomCode(): int
    {
        $code = random_int(0, PHP_INT_MAX);

        if (in_array($code, self::$codes)) {
            return self::getRandomCode();
        }

        self::$codes[] = $code;
        return $code;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getFunction(string $function): string
    {
        return sprintf('window.%s_%s', $function, $this->code);
    }
}
