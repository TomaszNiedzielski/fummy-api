<?php

namespace App\Interfaces;

interface SearchInterface
{
    /**
     * search users
     * 
     * @method  POST    api/search
     * @access  public
     */
    public function search(string $searchingWord);

    /**
     * Load only verified users
     * 
     * @method  POST    users/get
     * @access  public
     */
    public function getVerifiedUsers();
}