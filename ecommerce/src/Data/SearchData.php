<?php

namespace App\Data;

class SearchData
{

    /**
     * q
     *
     * @var string
     */
    public $q = "";

    /**
     * categories
     *
     * @var Category[]
     */
    public $regionCategories = [];

    /**
     * categories
     *
     * @var Category[]
     */
    public $grapeCategories = [];

    /**
     * categories
     *
     * @var Category[]
     */
    public $typeCategories = [];

    /**
     * max
     *
     * @var null|integer
     */
    public $max;

    /**
     * min
     *
     * @var null|integer
     */
    public $min;
}
