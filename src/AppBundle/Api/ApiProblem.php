<?php

namespace AppBundle\Api;

use Symfony\Component\HttpFoundation\Response;

/**
 * A wrapper for holding data to be used for a application/problem+json response
 */
class ApiProblem
{
    const TYPE_VALIDATION_ERROR = 'validation_error';
    const TYPE_INVALID_REQUEST_BODY_FORMAT = 'invalid_body_format';
    const TYPE_INVALID_CREDENTIALS = 'invalid_credentials';
    const TYPE_USER_OR_PASSWORD_IS_WRONG = 'invalid_user_or_pass';
    const TYPE_YOUR_IP_IS_BANNED = "ip_is_banned";
    const TYPE_ACCESS_DENIED = "access_denied";
    const TYPE_INVALID_PARAMETER_PASSED="invalid_parameter";
    const TYPE_INVALID_NUMBER_OF_FEATURED_IMAGES_FOR_PRODUCTS="invalid_number_of_featured_images_for_products";
    const TYPE_SINGLE_PRODUCT_WITHOUT_PROVIDING_LIST_PRICE="single_product_without_providing_list_price";
    const TYPE_SINGLE_PRODUCT_WITHOUT_PROVIDING_PRINTING_POSSIBILITIES="single product without providing printing possibilities";
    const TYPE_INVALID_DATA_PASSED_TO_SERVER="invalid data passed to server";
    const TYPE_YOU_DONT_HAVE_ACCESS_TO_THIS_CLUB="You don't have access to this club";
    const TYPE_YOU_DONT_HAVE_ACCESS_TO_THIS_RETAILER="You don't have access to this retailer";
    const TYPE_USER_DOSE_NOT_EXIST="User dose not exist";
    const THE_PATH_DOES_NOT_EXITS = 'is not here';

    private static $titles = array(
        self::TYPE_VALIDATION_ERROR            => 'There was a validation error.',
        self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Invalid JSON format sent.',
        self::TYPE_INVALID_CREDENTIALS         => 'Invalid credentials.',
        self::TYPE_ACCESS_DENIED               => "You don't have access to edit this part.",
        self::TYPE_USER_OR_PASSWORD_IS_WRONG   => 'Username or password is wrong.',
        self::TYPE_YOUR_IP_IS_BANNED           => 'Your IP is banned.',
        self::TYPE_INVALID_PARAMETER_PASSED          => 'Invalid parameter passed',
        self::TYPE_INVALID_NUMBER_OF_FEATURED_IMAGES_FOR_PRODUCTS          => 'There should be one featured image in product images',
        self::TYPE_SINGLE_PRODUCT_WITHOUT_PROVIDING_LIST_PRICE          => 'There should be at least one active list price for product',
        self::TYPE_SINGLE_PRODUCT_WITHOUT_PROVIDING_PRINTING_POSSIBILITIES          => 'There should be at least one printing possibility',
        self::TYPE_INVALID_DATA_PASSED_TO_SERVER          => 'Invalid data passed to server',
        self::TYPE_INVALID_DATA_PASSED_TO_SERVER=>"invalid data passed to server",
        self::TYPE_YOU_DONT_HAVE_ACCESS_TO_THIS_CLUB=>"You don't have access to this club",
        self::TYPE_YOU_DONT_HAVE_ACCESS_TO_THIS_RETAILER=>"You don't have access to this retailer",
        self::TYPE_USER_DOSE_NOT_EXIST=>"User dose not exist",
        self::THE_PATH_DOES_NOT_EXITS          => 'Invalid parameter passed2'
    );

    private $statusCode;

    private $type;

    private $title;

    private $extraData = array();

    public function __construct($statusCode, $type = null) {
        $this->statusCode = $statusCode;

        if ($type === null) {
            // no type? The default is about:blank and the title should
            // be the standard status code message
            $type = 'about:blank';
            $title = isset(Response::$statusTexts[$statusCode])
                ? Response::$statusTexts[$statusCode]
                : 'Unknown status code :(';
        } else {
            if (!isset(self::$titles[$type])) {
                throw new \InvalidArgumentException('No title for type ' . $type);
            }

            $title = self::$titles[$type];
        }

        $this->type = $type;
        $this->title = $title;
    }

    public function toArray() {
        return array_merge(
            $this->extraData,
            array(
                'status' => $this->statusCode,
                'type'   => $this->type,
                'title'  => $this->title,
            )
        );
    }

    public function set($name, $value) {
        $this->extraData[$name] = $value;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function getTitle() {
        return $this->title;
    }
}
