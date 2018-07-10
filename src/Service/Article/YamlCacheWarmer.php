<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 05/07/2018
 * Time: 11:15
 */

namespace App\Service\Article;


use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlCacheWarmer extends CacheWarmer
{
    public function isOptional()
    {
        return false;
    }
    public function warmUp($cacheDir)
    {
        try {
            $articles = Yaml::parseFile(__DIR__ . '/articles.yaml');
            $this->writeCacheFile($cacheDir.'/../yaml-article.php',serialize($articles));
        } catch (ParseException $exception) {
            printf('Unable to parse the YAML string: %s', $exception->getMessage());
        }
    }
}