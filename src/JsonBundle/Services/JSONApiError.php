<?php

namespace JsonBundle\Services;

use Neomerx\JsonApi\Contracts\Document\DocumentInterface;
use Neomerx\JsonApi\Contracts\Document\ErrorInterface;
use Neomerx\JsonApi\Contracts\Document\LinkInterface;
use Neomerx\JsonApi\Factories\Exceptions;
use Symfony\Component\HttpFoundation\Response;

class JSONApiError implements ErrorInterface
{
    /** @var int|string|null */
    protected $idx;

    /** @var null|array<string,\Neomerx\JsonApi\Contracts\Schema\LinkInterface> */
    protected $links;

    /** @var string|null */
    protected $status;

    /** @var string|null */
    protected $code;

    /** @var string|null */
    protected $title;

    /** @var string|null */
    protected $detail;

    /** @var array|null */
    protected $source;

    /** @var mixed|null */
    protected $meta;

    /**
     * @param int|string|null    $idx
     * @param LinkInterface|null $aboutLink
     * @param int|string|null    $status
     * @param int|string|null    $code
     * @param string|null        $title
     * @param string|null        $detail
     * @param array|null         $source
     * @param mixed|null         $meta
     */
    public function __construct(
        $idx = null,
        LinkInterface $aboutLink = null,
        $status = null,
        $code = null,
        $title = null,
        $detail = null,
        array $source = null,
        $meta = null
    ) {
        $this->checkIdx($idx);
        $this->checkCode($code);
        $this->checkTitle($title);
        $this->checkStatus($status);
        $this->checkDetail($detail);
    }

    /** @inheritdoc */
    public function getId()
    {
        return $this->idx;
    }

    /** @inheritdoc */
    public function getLinks()
    {
        return $this->links;
    }

    /** @inheritdoc */
    public function getStatus()
    {
        return $this->status;
    }

    /** @inheritdoc */
    public function getCode()
    {
        return $this->code;
    }

    /** @inheritdoc */
    public function getTitle()
    {
        return $this->title;
    }

    /** @inheritdoc */
    public function getDetail()
    {
        return $this->detail;
    }

    /** @inheritdoc */
    public function getSource()
    {
        return $this->source;
    }

    /** @inheritdoc */
    public function getMeta()
    {
        return $this->meta;
    }

    /** @param int|null|string $idx */
    public function setIdx($idx)
    {
        $this->idx = $idx;
    }

    /** @param array|null $aboutLink */
    public function setLinks($aboutLink)
    {
        $this->links = ($aboutLink === null ? null : [DocumentInterface::KEYWORD_ERRORS_ABOUT => $aboutLink]);
    }

    /** @param null|string $status */
    public function setStatus($status)
    {
        $this->status = ($status !== null ? (string)$status : null);
    }

    /** @param null|string $code */
    public function setCode($code)
    {
        $this->code = ($code !== null ? (string)$code : null);
    }

    /** @param null|string $title */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /** @param null|string $detail */
    public function setDetail($detail)
    {
        $this->detail = $detail;
    }

    /** @param array|null $source */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /** @param mixed|null $meta */
    public function setMeta($meta)
    {
        $this->meta = $meta;
    }

    /** @param int|string|null $idx */
    private function checkIdx($idx)
    {
        ($idx === null || is_int($idx) === true ||
            is_string($idx) === true) ?: Exceptions::throwInvalidArgument('idx', $idx);
    }

    /** @param string|null $title */
    private function checkTitle($title)
    {
        ($title === null || is_string($title) === true) ?: Exceptions::throwInvalidArgument('title', $title);
    }

    /** @param string|null $detail */
    private function checkDetail($detail)
    {
        ($detail === null || is_string($detail) === true) ?: Exceptions::throwInvalidArgument('detail', $detail);
    }

    /** @param int|string|null $status */
    private function checkStatus($status)
    {
        $isOk = ($status === null || is_int($status) === true || is_string($status) === true);
        $isOk ?: Exceptions::throwInvalidArgument('status', $status);
    }

    /** @param int|string|null $code */
    private function checkCode($code)
    {
        $isOk = ($code === null || is_int($code) === true || is_string($code) === true);
        $isOk ?: Exceptions::throwInvalidArgument('code', $code);
    }

    /**
     * @param string $message
     * @param array $source
     * @return $this
     */
    public function getBadRequestError($message, array $source, $title = null)
    {
        /** @var JSONApiError $newObject */
        $newObject = new $this;

        $title = ($title) ? $title : $message;

        $newObject->setCode(Response::HTTP_BAD_REQUEST);
        $newObject->setTitle($title);
        $newObject->setDetail($message);
        $newObject->setSource($source);

        return $newObject;
    }

    /**
     * @param string $message
     * @param array $source
     * @param string $title
     * @return object
     */
    public function getForbiddenError($message, array $source, $title = null)
    {
        /** @var JSONApiError $newObject */
        $newObject = new $this;

        $title = ($title) ? $title : $message;

        $this->setCode(Response::HTTP_FORBIDDEN);
        $this->setTitle($title);
        $this->setDetail($message);
        $this->setSource($source);

        return $newObject;
    }

    /**
     * @param string $errorName
     * @param string $message
     * @param array $source
     * @param string $title
     * @return object
     */
    public function getErrorObjectByErrorName($errorName, $message, array $source, $title = null)
    {
        /** @var JSONApiError $newObject */
        $newObject = new $this;

        $title = ($title) ? $title : $message;
        $code = (in_array($errorName, self::ERRORS)) ? self::ERRORS[$errorName] : self::ERRORS['ok'];

        $this->setCode($code);
        $this->setTitle($title);
        $this->setDetail($message);
        $this->setSource($source);

        return $newObject;
    }

    const ERRORS = [
            'ok' => Response::HTTP_OK,
            'forbiden' => Response::HTTP_FORBIDDEN,
            'badRequest' => Response::HTTP_BAD_REQUEST
        ];
}
