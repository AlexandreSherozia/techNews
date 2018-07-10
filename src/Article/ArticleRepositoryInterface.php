<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 05/07/2018
 * Time: 11:32
 */

namespace App\Article;


use App\Entity\Article;

interface ArticleRepositoryInterface
{
    public function find($id) : ?Article;


    /**
     * @return iterable|null
     * retourne la liste de tous les aarticles
     */
    public function findAll(): ?iterable;

    /**
     * @return iterable
     * Retourne les 5 dernières  articles
     */
    public function findLastFiveArticles(): ?iterable;

    /**
     * @return int
     * Retourne le nombre d'elements de chaque source
     */
    public function count(): int;


    //public function findBy();

}