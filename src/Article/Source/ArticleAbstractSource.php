<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 05/07/2018
 * Time: 11:53
 */

namespace App\Article\Source;


use App\Article\ArticleCatalogue;
use App\Article\ArticleRepositoryInterface;
use App\Entity\Article;

//C'est le "Catalogue" du médiator, qui est le seul en contact avec lui
abstract class ArticleAbstractSource implements ArticleRepositoryInterface
{
    protected $catalogue;

    /**
     * @param mixed $catalogue
     */
    public function setCatalogue(ArticleCatalogue $catalogue): void
    {
        $this->catalogue = $catalogue;
    }

    /**
     * @param iterable $article
     * @return Article|null
     */
    abstract protected function createFromArray(iterable $article): ?Article;
}