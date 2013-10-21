<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclZfGenericMapperTests\TestAssets;

use SclZfGenericMapper\CommonMapperMethodsTrait;

/**
 * Used to expose CommonMapperMethodsTrait methods for testing.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class TestMapper
{
    use CommonMapperMethodsTrait;

    public function __construct($prototype)
    {
        $this->setPrototype($prototype);
    }
}
