<?php

namespace AppBundle\Controller\Api;

use JsonBundle\Controller\BaseController;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\EncoderOptions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use JsonBundle\Category\Schema as CategorySchema;
use JsonBundle\Article\Schema as ArticleSchema;
use \Proxies\__CG__\AppBundle\Entity\Category as CategoryProxy;
use \Proxies\__CG__\AppBundle\Entity\Article as ArticleProxy;
use AppBundle\Entity\Article;
use AppBundle\Entity\Category;

use JsonBundle\Category\Hydrator as CategoryHydrator;

/**
 * Class CategoryController
 * @package AppBundle\Controller\Api
 */
class CategoryController extends BaseController
{
    /**
     * @return CategoryHydrator
     */
    protected function getHydrator()
    {
        return $this->get('jsonapi.category.hydrator');
    }

    /**
     *
     * @Route("/api/category", name="post_category")
     *
     * @return Response
     */
    public function postCategory()
    {
        return $this->postEntity();
    }

    /**
     * @Route("/api/categories", name="get_categories")
     * @Method("GET")
     *
     * @return Response
     */
    public function getCategoriesAction()
    {
        return $this->getList();
    }

    /**
     * @Route("/api/categories/{id}", name="get_category")
     * @Method("GET")
     *
     * @return Response
     */
    public function getCategoryAction($id)
    {
        return $this->getEntity($id);
    }

    /**
     * @param $id
     *
     * @Route("/api/categories/{id}", name="put_category")
     * @Method("PUT")
     *
     * @return Response
     */
    public function putCategory($id)
    {
        return $this->putEntity($id);
    }

    /**
     * @return \Neomerx\JsonApi\Contracts\Encoder\EncoderInterface
     */
    protected function getEncoder()
    {
        return Encoder::instance([

            CategoryProxy::class =>CategorySchema::class,
            Category::class => CategorySchema::class,

            ArticleProxy::class => ArticleSchema::class,
            Article::class => ArticleSchema::class,

        ], new EncoderOptions(JSON_PRETTY_PRINT, stripslashes($this->link)));
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return Category::class;
    }
}
