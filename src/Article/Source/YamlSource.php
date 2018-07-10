<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 05/07/2018
 * Time: 12:00
 */

namespace App\Article\Source;


use App\Controller\HelperTrait;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use App\Service\Article\YamlProvider;
use Tightenco\Collect\Support\Collection;

class YamlSource extends ArticleAbstractSource
{
    use HelperTrait;

    private $articles;

    /**
     * YamlSource constructor.
     * @param $articles
     */
    public function __construct(YamlProvider $yamlProvider)
    {
        $this->articles = new Collection($yamlProvider->getArticles());
    }

    /**
     * Retoune une article sur la base de
     * son identifiant unique
     * @param $id
     * @return Article|null
     */
    public function find($id): ?Article
    {
        $article = $this->articles->firstWhere('id', $id);
        return $article == null ? null : $this->createFromArray($article);
    }

    /**
     * @return iterable|null
     * retourne la liste de tous les aarticles
     */
    public function findAll(): ?iterable
    {
        $articles = new Collection();

        foreach ($this->articles as $article) {
            $articles[] = $this->createFromArray($article);
        }

        return $articles;
    }

    /**
     * @return iterable
     * Retourne les 5 dernières  articles
     */
    public function findLastFiveArticles(): ?iterable
    {
        /* @var $articles Collection*/
        $articles = $this->findAll();

        return $articles->sortBy('datecreation')->slice(-5);
    }

    /**
     *
     * @return int
     * Retourne le nombre d'elements de chaque source
     */
    public function count(): int
    {
        return $this->articles->count();
    }

    protected function createFromArray(iterable $article): ?Article
    {
        $tmp = (object)$article;

        #on contruit l'objet Category

        $category = new Category();
        $category->setId($tmp->categorie['id']);
        $category->setName($tmp->categorie['libelle']);
        $category->setSlug($this->slugify($tmp->categorie['libelle']));

        #construction objet Auteur
        $user = new User();
        $user->setId($tmp->auteur['id']);
        $user->setFirstname($tmp->auteur['prenom']);
        $user->setLastname($tmp->auteur['nom']);

        $date = new \DateTime();


        return new Article(
            $tmp->id,
            $tmp->titre,
            $this->slugify($tmp->titre),
            $tmp->contenu,
            $tmp->featuredimage,
            $tmp->special,
            $tmp->spotlight,
            $date->setTimestamp($tmp->datecreation),
            $category,
            $user
        );

    }
}