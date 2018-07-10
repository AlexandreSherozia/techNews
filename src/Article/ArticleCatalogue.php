<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 05/07/2018
 * Time: 11:48
 */

namespace App\Article;


use App\Article\Source\ArticleAbstractSource;
use App\Entity\Article;
use App\Exception\DuplicateCatalogueArticleException;
use Tightenco\Collect\Support\Collection;

class ArticleCatalogue implements ArticleCatalogueInterface
{
    private $sources;

    /**
     * ArticleCatalogue constructor.
     * @param $sources
     */


    public function addSource(ArticleAbstractSource $source): void
    {
        $this->sources[] = $source;
    }

    public function setSources(iterable $sources): void
    {
        $this->sources = $sources;
    }

    public function getSources(): iterable
    {
        return $this->sources;
    }

    public function find($id): ?Article
    {
        $articles = new Collection();

        #Je parcours mes sources à la recherche de mon article
        /* @var $source ArticleAbstractSource */
        foreach ($this->sources as $source) {
            $article = $source->find($id);

            /*  Si ma source ne me renvoie  pas null,  Je rajoute à ma source*/
            if (null !== $article) {
                $articles [] = $article;
            }
            //Vérification de doublon

            /*if ($articles->count() > 1) {
                throw new DuplicateCatalogueArticleException(sprintf(
                    'Return value of %s cannot return more than one record on line %s',
                    get_class(($this).'::'.__FUNCTION__.'()', __LINE__
                ));
            }*/

            return $articles->pop();


        }
    }

    /**
     * @return iterable|null
     * retourne la liste de tous les articles
     */
    public function findAll(): ?iterable
    {
        return $this->iterateOverSources('findAll')
            ->sortBy('createdDate');
    }


    /**
     * @return iterable | null
     * Retourne les 5 dernières  articles
     */
    public function findLastFiveArticles(): iterable
    {
        return $this->iterateOverSources('findLastFiveArticles')
            ->slice(-5);
    }

    /**
     * @return int
     * Retourne le nombre d'elements de chaque source
     */
    public function count(): int
    {
        // TODO: Implement count() method.
    }

    private function iterateOverSources(string $functionToCall): Collection
    {
        $articles = new Collection();

        /* @var $source ArticleAbstractSource */
        /* @var $article Article */
        foreach ($this->sources as $source) {
            foreach ($source->$functionToCall() as $article) {
                $articles[] = $article;
            }
        }

        return $articles;
    }
}