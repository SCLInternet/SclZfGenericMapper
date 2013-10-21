<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclZfGenericMapper\Exception;

/**
 * RuntimeException
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
    /**
     * 'Multiple results were returned when only 1 was expected.'
     *
     * @return RuntimeException
     */
    public static function multipleResultsFound()
    {
        return new self('Multiple results were returned when only 1 was expected.');
    }

    /**
     * 'SclZfGenericMapper\Exception\RuntimeException'
     *
     * @return RuntimeException
     */
    public static function setPrototypeCalledAgain()
    {
        return new self('setPrototype() can only be called once.');
    }


    /**
     * 'Prototype entity class has not been set.'
     *
     * @return RuntimeException
     */
    public static function prototypeNotSet()
    {
        return new self( 'Prototype entity class has not been set.');
    }
}
