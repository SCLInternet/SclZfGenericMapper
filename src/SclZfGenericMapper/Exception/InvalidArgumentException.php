<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclZfGenericMapper\Exception;

/**
 * InvalidArgumentException
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
    /**
     * 'Entity must be an instance of "%s"; got "%s".'
     *
     * @param  string $expected
     * @param  object $actual
     *
     * @return InvalidArgumentException
     */
    public static function invalidEntityType($expected, $actual)
    {
        return new self(sprintf(
            'Entity must be an instance of "%s"; got "%s".',
            $expected,
            is_object($actual) ? get_class($actual) : gettype($actual)
        ));
    }

    /**
     * 'Expected an object; got "string".'
     *
     * @param  mixed $got
     *
     * @return InvalidArgumentException
     */
    public static function objectExpected($got)
    {
        return new self(sprintf('Expected an object; got "%s".', gettype($got)));
    }
}
