<?php

namespace App\Data;

/**
 * SearchData
 */
class SearchData
{
    /**
     * page
     *
     * @var integer
     */
    public $page = 1;

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
     * Year Categories (Categories with name = "year")
     *
     * @var Category[]
     */
    public $yearCategories = [];

    /**
     * designationCategories
     *
     * @var Category[]
     */
    public $designationCategories = [];

    /**
     * literCategories
     *
     * @var Category[]
     */
    public $literCategories = [];

    /**
     * alcoolCategories
     *
     * @var Category[]
     */
    public $alcoolCategories = [];

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
