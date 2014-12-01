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

interface DomainManagerInterface {

    public function get($id);

    public function find($id);

    public function findOneBy(array $args);

    public function findBy(array $args);

    public function findAll();

    public function create($entity);

    public function save($entity);

    public function delete($id);
} 