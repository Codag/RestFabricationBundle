<?php
/*
 * This file is part of the [name] package.
 *
 * (c) Marc Juchli <mail@marcjuch.li>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Codag\RestFabricationBundle\Exception;


use Symfony\Component\HttpKernel\Exception\HttpException;

class RestException extends HttpException
{

    public function __construct($code = 0, $message)
    {
        parent::__construct($code, $message);
    }

}