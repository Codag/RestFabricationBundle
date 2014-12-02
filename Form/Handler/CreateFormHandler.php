<?php
/*
 * This file is part of the [name] package.
 *
 * (c) Marc Juchli <mail@marcjuch.li>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Codag\RestFabricationBundle\Form\Handler;

use Codag\RestFabricationBundle\DomainManager\DomainManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Codag\RestFabricationBundle\Exception\InvalidFormException;

class CreateFormHandler {
    private $manager;

    public function __construct(DomainManagerInterface $manager){
        $this->manager = $manager;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return Entity
     * @throws InvalidFormException
     */
    public function handle(FormInterface $form, Request $request){
        $form->submit($request, 'PATCH' !== $request->getMethod());

        if($form->isValid()){
            $validData = $form->getData();
            $this->manager->create($validData);

            return $validData;
        }

        throw new InvalidFormException('Invalid submitted data', $form);

    }
} 