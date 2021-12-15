<?php

namespace App\Interfaces;

interface SearchInterface
{
    /**
     * search users
     * 
     * @method  GET  api/search-results
     */
    public function search(string $q);
}