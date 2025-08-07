<?php
/**
 * efxphp (http://emilmalinov.com/efxphp)
 *
 * @copyright Copyright (c) 2015 Emil Malinov
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      http://github.com/emilkm/efxphp
 * @package   efxphp
 */

namespace emilkm\efxphp;

/**
 * @author  Emil Malinov
 * @package efxphp
 */
class Request
{
    public $source;
    public $operation;
    public $params;

    /**
     * @param string   $source
     * @param stirng   $operation
     * @param mixed    $params
     */
    public function __construct($source, $operation, $params)
    {
        $this->source = $source;
        $this->operation = $operation;
        $this->params = $params;
    }
}
