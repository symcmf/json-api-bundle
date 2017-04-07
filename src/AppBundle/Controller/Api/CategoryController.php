<?php

namespace AppBundle\Controller\Api;

use JsonBundle\Controller\BaseController;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\EncoderOptions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use JsonBundle\Category\Schema as CategorySchema;
use JsonBundle\Article\Schema as ArticleSchema;
use \Proxies\__CG__\AppBundle\Entity\Category as CategoryProxy;
use \Proxies\__CG__\AppBundle\Entity\Article as ArticleProxy;
use AppBundle\Entity\Article;
use AppBundle\Entity\Category;

/**
 * Class CategoryController
 * @package AppBundle\Controller\Api
 */
class CategoryController extends BaseController
{
    /**
     * @Route("/api/categories", name="get_categories")
     */
    public function getArticlesAction(Request $request)
    {
        $category = $this->getList($request);


        echo $this->viewObject($request, $category);
        die();
    }

    protected function getEncoder()
    {
        return Encoder::instance([

            CategoryProxy::class =>CategorySchema::class,
            Category::class => CategorySchema::class,

            ArticleProxy::class => ArticleSchema::class,
            Article::class => ArticleSchema::class,

        ], new EncoderOptions(JSON_PRETTY_PRINT, stripslashes('http://example.com/api')));
    }
}
