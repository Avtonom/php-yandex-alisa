<?php

namespace yandex\alisa\core;

class StringHelper
{
    /**
     * @param string $expected
     * @param string $actual
     *
     * @return bool
     */
    public static function equals($expected, $actual)
    {
        return self::prepare($expected) == self::prepare($actual);
    }

    /**
     * @param string $str
     *
     * @return string
     */
    protected static function prepare($str)
    {
        return self::lower(self::clear($str));
    }

    /**
     * @param string $str
     *
     * @return string
     */
    protected static function clear($str)
    {
        return mb_ereg_replace("\W|_","", $str);
    }

    /**
     * @param string $str
     *
     * @return string
     */
    protected static function lower($str)
    {
        return mb_strtolower($str, 'UTF-8');
    }
}