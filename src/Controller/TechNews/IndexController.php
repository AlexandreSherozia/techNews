<?php

namespace App\Controller\TechNews;


use App\Article\ArticleCatalogue;
use App\Entity\Article;
use App\Entity\Category;
use App\Exception\DuplicateCatalogueArticleException;
use App\Service\Article\YamlProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

class IndexController extends Controller
{
    /**
     * Page d'Accueil de notre Site Internet
     * @param YamlProvider $yamlProvider
     * @return Response
     */
    public function index(YamlProvider $yamlProvider)
    {

        # Récupération des Articles depuis YamlProvider
        # $articles = $yamlProvider->getArticles();
        # dump($articles);

        # Connexion au Repository
        $repository = $this->getDoctrine()
            ->getRepository(Article::class);

        # Récupération des articles depuis la BDD
        $articles = $repository->findAll();
        $spotlight = $repository->findSpotlightArticles();

        # return new Response("<html><body><h1>PAGE D'ACCUEIL</h1></body></html>");
        return $this->render('index/index.html.twig', [
            'articles' => $articles,
            'spotlight' => $spotlight
        ]);
    }

    /**
     * @param YamlProvider $yamlProvider
     * @Route("/yamltest", name="yamltest")
     */
    public function yaml(YamlProvider $yamlProvider, TranslatorInterface $translator)
    {
        $articles = $yamlProvider->getArticles();
        //$translated = $translator->trans('Symfony is great');
        //$translated = 'Symfony is great';


        return $this->render('test.html.twig', [
            'articles' => $articles,
            //'translated' => $translated
        ]);
    }

    /**
     * Afficher les Articles d'une Catégorie
     * @Route("/categorie/{category<\w+>}",
     *  name="index_category",
     *     methods={"GET"},
     *     defaults={"category":"tout"})
     * @param $category
     * @return Response
     */
    public function category($category)
    {
        # Récupération de la catégorie
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['slug' => $category]);

        # Si la catégorie est null, on redirige l'utilisateur
        if (null === $category) {
            return $this->redirectToRoute('index', [], Response::HTTP_MOVED_PERMANENTLY);
        }

        # Récupérer les articles de la catégorie
        $articles = $category->getArticles();

        # return new Response("<html><body><h1>PAGE CATEGORIE : $category</h1></body></html>");
        return $this->render('index/category.html.twig', [
            'category' => $category,
            'articles' => $articles
        ]);
    }

    /**
     * Affiche un Article
     * @Route("/{_locale}/{category}/{slug}_{id<\d+>}.html",
     *     name="index_article")
     * @param Article $article
     * @param $id
     * @return Response
     */
   // public function article(Article $article = null, $id)
    public function article(ArticleCatalogue $catalogue, $id)
    {
        # Récupération de mon Article depuis la BDD
        # $article = $this->getDoctrine()
        #     ->getRepository(Article::class)
        #     ->find($id);


        try {
            $article = $catalogue->find($id);
        } catch (DuplicateCatalogueArticleException $articleException) {
            return $this->redirectToRoute('index', [], Response::HTTP_MOVED_PERMANENTLY);
        }

        # Récupérer les suggestions d'articles
        $suggestions = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticlesSuggestions($article->getId(), $article->getCategory()->getId());

        # /business/une-formation-symfony-a-paris_8796456.html
        # Transmission des données à la vue
        return $this->render('index/article.html.twig', [
            'article' => $article,
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Génération de la Sidebar
     * @param Article|null $article
     * @return Response
     */
    public function sidebar(?Article $article = null, ArticleCatalogue $catalogue) {

        # Récupération du Répository
        $repository = $this->getDoctrine()
            ->getRepository(Article::class);

        # Récupération des 5 derniers articles
        $articles = $repository->findLastFiveArticles();

        # Récupérations des articles à la position "special"
        $specials = $repository->findSpecialArticles();

        # Rendu de la vue
        return $this->render('components/_sidebar.html.twig', [
           'articles' => $articles,
           'specials' => $specials,
            'article' => $article
        ]);

    }
}
