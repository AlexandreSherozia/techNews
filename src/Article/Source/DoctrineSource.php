<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 05/07/2018
 * Time: 11:58
 */

namespace App\Article\Source;


use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;

class DoctrineSource extends ArticleAbstractSource
{

    private $repository;
    private $entity = Article::class;

    /**
     * DoctrineSource constructor.
     * @param $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->repository = $manager->getRepository($this->entity);
    }

    public function find($id): ?Article
    {
        return $this->repository->find($id);
    }

    /**
     * @return iterable|null
     * retourne la liste de tous les aarticles
     */
    public function findAll(): ?iterable
    {
        return $this->repository->findAll();
    }

    /**
     * @return iterable
     * Retourne les 5 dernières  articles
     */
    public function findLastFiveArticles(): iterable
    {
        return $this->repository->findLastFiveArticles();
    }

    /**
     * @return int
     * Retourne le nombre d'elements de chaque source
     */
    public function count(): int
    {
        return $this->repository->findTotalArticles();
    }

    protected function createFromArray(iterable $article): ?Article
    {
        return null;
    }

}