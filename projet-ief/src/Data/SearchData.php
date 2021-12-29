<?php
namespace App\Data;

class SearchData 
{
    /**
     * @var string
     */
    public $q ;

    /**
     * @var Category[]
     */
    public $categories = [];

    /**
     * @var boolean
     */
    public $limite = null;

    /**
     * @var boolean
     */
    public $date = null;

    /**
     * @var integer
     */
    public $page = 1;
}