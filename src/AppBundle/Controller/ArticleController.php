<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use AppBundle\Entity\Category;
use JsonBundle\Validator;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\EncoderOptions;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Proxies\__CG__\AppBundle\Entity\Category as CategoryProxy;
use \Proxies\__CG__\AppBundle\Entity\Article as ArticleProxy;
use JsonBundle\Category\Schema as CategorySchema;
use JsonBundle\Article\Schema as ArticleSchema;

class ArticleController extends Controller
{
    public function getArticlesAction()
    {
        $encoder = Encoder::instance([
            Article::class => ArticleSchema::class,
        ], new EncoderOptions(JSON_PRETTY_PRINT, 'http://example.com/api'));

        $article = $this->getDoctrine()->getRepository('AppBundle:Article')->find(1);

        dump(PHP_EOL . $encoder->encodeData($article) . PHP_EOL);

        $encoder = Encoder::instance([
            CategoryProxy::class => CategorySchema::class,
            ArticleProxy::class => ArticleSchema::class,
            Category::class => CategorySchema::class,
            Article::class => ArticleSchema::class,
        ], new EncoderOptions(JSON_PRETTY_PRINT, stripslashes('http://example.com/api')));

        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find(1);

        dump(PHP_EOL . $encoder->encodeData($category) . PHP_EOL);

        (new Validator())->validate();

        die();
    }
}