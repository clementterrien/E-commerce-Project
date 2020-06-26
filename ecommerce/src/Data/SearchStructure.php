<?php

namespace App\Data;

use App\Repository\CategoryRepository;

/**
 * SearchStructure used to store how a search is structured 
 * The difference with SearchData is it will store only categories names and not the entire category object
 */
class SearchStructure
{
    /**
     * region
     *
     * @var array||string[]
     */
    protected $regions = [];

    /**
     * grapes 
     *
     * @var array||string[]
     */
    protected $grapes = [];

    /**
     * getRegions
     *
     * @return string[]
     */
    public function getRegions(): array
    {
        return $this->regions;
    }
    /**
     * setRegions
     *
     * @param  string[] $regions
     * @return self
     */
    public function setRegions(array $regions): self
    {
        $this->regions = $regions;

        return $this;
    }
    /**
     * getGrapes
     *
     * @return string[]
     */
    public function getGrapes(): array
    {
        return $this->grapes;
    }
    /**
     * setGrapes
     *
     * @param  string[] $grapes
     * @return self
     */
    public function setGrapes(array $grapes): self
    {
        $this->grapes = $grapes;

        return $this;
    }
}
