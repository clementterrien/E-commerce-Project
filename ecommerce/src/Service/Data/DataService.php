<?php

namespace App\Service\Data;

use App\Data\SearchData;
use Error;
use App\Data\SearchStructure;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * DataService
 */
class DataService
{
    protected $categoryRepo;
    protected $regions = [];

    /**
     * SearchStructure
     *
     * @var SearchStructure
     */
    protected $SearchStructure;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepo = $categoryRepository;
        $this->SearchStructure = new SearchStructure;
    }

    /**
     * getDataStructure
     *
     * @param  Request $request
     * @return StructureData
     */
    public function getDataStructure(Request $request): SearchStructure
    {
        $this->setRequest($request);
        $this->fillRegion();
        $this->fillGrapes();

        return $this->SearchStructure;
    }

    public function setRequest(Request $request)
    {
        return $this->request = $request;
    }

    public function fillRegion()
    {
        $this->isRequestSetted();

        $requestRegion = $this->request->get('regionCategories', []);

        foreach ($requestRegion as $key => $value) {
            $regionCategoryName = ($this->categoryRepo->findOneBy(['id' => $value]))->getValue();
            array_push($this->regions, $regionCategoryName);
        }
        $this->SearchStructure->setRegions($this->regions);
    }

    public function fillGrapes()
    {
        $this->isRequestSetted();

        $requestRegion = $this->request->get('regionCategories', []);
        $regions = [];
        foreach ($requestRegion as $key => $value) {
            $regionCategoryName = ($this->categoryRepo->findOneBy(['id' => $value]))->getValue();
            array_push($this->regions, $regionCategoryName);
        }

        $this->SearchStructure->setRegions($regions);
    }

    /**
     * isRequestSetted verifies if the request has been setted correctly in this object
     *
     * @return bool
     */
    public function isRequestSetted(): bool
    {
        if (empty($this->request)) {
            throw new Error('This Service must have a request => use $this->setRequest before calling any method !');
        } else {
            return true;
        }
    }
}
