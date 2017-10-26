<?php

namespace SAREhub\Commons\Misc;

interface HashStorage
{

    public function open();

    public function close();

    /**
     * @param $id
     * @return string
     */
    public function findById($id);

    /**
     * @param array $hashes
     */
    public function insertMulti(array $hashes);

    /**
     * @param array $hashes
     */
    public function updateMulti(array $hashes);

}