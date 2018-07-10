<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 04/07/2018
 * Time: 11:52
 */

namespace App\Article;


use App\Service\Article\MediatorInterface;

class ArticleMediator implements MediatorInterface
{
    private $colleagues = [];

 /**
 * ArticleMediator constructor.
 * @param $colleague
 */
 public function __construct($colleague)
    {
        $this->colleagues[] = $colleague;
    }

    public function sendResponse($content)
    {
        // TODO: Implement sendResponse() method.
    }
    public function makeRequest()
    {
        // TODO: Implement makeRequest() method.
    }
    public function queryDb()
    {

    }
}