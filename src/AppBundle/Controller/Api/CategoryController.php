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
     *
     * @Route("/api/category", name="post_category")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postCategory(Request $request)
    {
        $this->hydrator = new CategoryHydrator($this->getDoctrine()->getManager());

        return $this->postEntity($request);
    }

    /**
     * @Route("/api/categories", name="get_categories")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getCategoriesAction(Request $request)
    {
        $category = $this->getList($request);

        return $this->createResponse($this->viewObject($request, $category), Response::HTTP_OK);
    }

    /**
     * @Route("/api/categories/{id}", name="get_category")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getCategoryAction($id, Request $request)
    {
        $category = $this->getEntity($id, $request);

        return $this->createResponse($this->viewObject($request, $category), Response::HTTP_OK);
    }

    /**
     * @param $id
     * @param Request $request
     *
     * @Route("/api/categories/{id}", name="put_category")
     * @Method("PUT")
     *
     */
    public function putCategory($id, Request $request)
    {
        // TODO sure that it's good idea. Need to fix
        $this->hydrator = new CategoryHydrator($this->getDoctrine()->getManager());

        $this->putEntity($id, $request);
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

        ], new EncoderOptions(JSON_PRETTY_PRINT, stripslashes('http://example.com/api')));
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return Category::class;
    }
}
