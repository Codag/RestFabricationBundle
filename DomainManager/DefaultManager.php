<?php
/*
 * This file is part of the [name] package.
 *
 * (c) Marc Juchli <mail@marcjuch.li>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Codag\RestFabricationBundle\DomainManager;

use Doctrine\ORM\EntityManager;
use Codag\RestFabricationBundle\Exception\RessourceNotFoundException;


class DefaultManager {
    protected $entityManager;
    protected $entityName;

    public function __construct(EntityManager $entityManager, $entityName){
        $this->entityManager = $entityManager;
        $this->entityName = $entityName;
    }

    public function find($id){
        return $this->entityManager->getRepository($this->entityName)->find($id);
    }

    public function findOneBy($args){
        $entity = $this->entityManager->getRepository($this->entityName)->findOneBy($args);
        if(!$entity){
            throw new RessourceNotFoundException('Entity');
        }
        return $entity;
    }

    public function findBy($args){
        $entity = $this->entityManager->getRepository($this->entityName)->findBy($args);
        if(!$entity){
            throw new RessourceNotFoundException('Entity');
        }
        return $entity;
    }

    public function findAll(){
        return $this->entityManager->getRepository($this->entityName)->findAll();
    }

    public function create($entity){
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function save($entity){
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function delete($id){
        $entity = $this->find($id);
        if(!$entity){
            throw new RessourceNotFoundException('Entity', $id);
        }
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
} 