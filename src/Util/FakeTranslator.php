<?php

namespace App\Util;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class FakeTranslator
 * @package App\Util
 *
 * @method string getLocale()
 */
class FakeTranslator implements TranslatorInterface
{
    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null)
    {
        return $id;
    }
}