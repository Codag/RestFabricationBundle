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


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResourceNotFoundException extends NotFoundHttpException
{
    protected $form;

    public function __construct($entityName, $id = null, $form = null)
    {
        $message = $entityName . " not found";
        if($id != null) {
            $message .= "with identifier: " . $id;
        }
        parent::__construct($message);
        $this->form = $form;
    }

}