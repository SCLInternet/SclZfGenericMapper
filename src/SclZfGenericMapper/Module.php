<?php
/**
 * SclZfGenericMapper (https://github.com/SCLInternet/SclZfGenericMapper)
 *
 * @link https://github.com/SCLInternet/SclZfGenericMapper for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace SclZfGenericMapper;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

/**
 * ZF2 module class for the SclZfGenericMapper module.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Module implements AutoloaderProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
}
