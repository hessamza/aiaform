<?php

namespace AppBundle\Controller;


use AppBundle\Exceptions\ApiProblem;
use AppBundle\Exceptions\ApiProblemException;
use Doctrine\ORM\QueryBuilder;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class BaseController extends Controller
{
    /**
     * @param $data
     * @param array $groups
     * @return mixed|string
     */
    protected function serializeToJson($data, $groups = array('Default')) {
        $context = new SerializationContext();
        $context->setSerializeNull(true);
        $context->setGroups($groups);
        $context->enableMaxDepthChecks();
        $json = $this->container->get('jms_serializer')
            ->serialize($data, 'json', $context);

        return $json;
    }

    /**
     * @param $data
     * @param int $statusCode
     * @param array $groups
     * @return Response
     */
    protected function createApiResponse($data, $statusCode = 200, $groups = array('Default')) {
        $response=new Response(
            $this->serializeToJson($data, $groups), $statusCode, array(
            'Content-Type' => 'application/json',
        ));
        return $response;
    }

    /**
     * @param FormInterface $form
     */
    public function throwApiProblemValidationException(FormInterface $form) {
        $errors = $this->getErrorsFromForm($form);

        $apiProblem = new ApiProblem(
            400,
            $this->get("translator")->trans("validation error",[],"validators")
        );
        $apiProblem->set('errors', $errors);
        throw new ApiProblemException($apiProblem);
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    protected function getErrorsFromForm(FormInterface $form) {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors['items'][] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if ( ! $child->isValid()) {
                $errors[$child->getName()] = $this->getErrorsFromForm($child);
            }
        }

        return $errors;
    }

    /**
     * @param string $title
     * @param int $statusCode
     * @return ApiProblemException
     */
    protected function createApiProblemException($title,$statusCode=404){
        return new ApiProblemException(new ApiProblem($statusCode,$title));
    }

    protected function queryStringDecoder(array $array) {
        foreach ($array as &$tmp) {
            if (is_array($tmp)) {
                $tmp = $this->queryStringDecoder($tmp);
            } else {
                if ((strpos($tmp, "@") == false) && (strpos($tmp, "//") == false)) {
                    $tmp = urlencode($tmp);
                }
            }
        }

        return $array;
    }

    /**
     * @param $className
     * @param QueryBuilder $query
     * @param $sortBy
     * @param $sortType
     * @param $default
     * @param array $extraFields
     * @return QueryBuilder
     */
    protected function applySort($className, QueryBuilder $query, $sortBy, $sortType, $default, $extraFields = [],$aliases=[]) {
        $alias          = current($query->getDQLPart('from'))->getAlias();
        $sortByVariable = (in_array($sortBy, $extraFields)) ? (isset($aliases[$sortBy])?$aliases[$sortBy]:"") : $alias . '.' . $sortBy;
        if (isset($sortBy) && isset($sortType)) {
            $this->getClassMetaDataProperties(
                $className, [
                'sortBy'   => $sortBy,
                'sortType' => $sortType
            ], $extraFields);
            $query->orderBy($sortByVariable, $sortType);
        } elseif (isset($sortBy)) {
            $this->getClassMetaDataProperties($className, ['sortBy' => $sortBy], $extraFields);
            $query->addOrderBy($sortByVariable, "DESC");
        } else {
            $this->getClassMetaDataProperties($className, [], $extraFields);
            $query->addOrderBy($default, "DESC");
        }

        return $query;
    }

    /**
     * @param $className
     * @param $tempSearch
     * @param array $extraFields
     * @param array $excludeFields
     * @return mixed
     */
    protected function getClassMetaDataProperties($className, $tempSearch, $extraFields = [], $excludeFields = []) {
        $sortBy          = isset($tempSearch['sortBy']) ? $tempSearch['sortBy'] : 'id';
        $sortType        = isset($tempSearch['sortType']) ? $tempSearch['sortType'] : 'DESC';
        $sortTypeArray   = ['asc', 'desc'];
        $entityFieldName = $this->getDoctrine()->getManager()->getClassMetadata($className)->getFieldNames();
        $allFieldNames   = array_merge(
            ['sortBy', 'sortType', 'page', 'count'],
            $extraFields,
            $entityFieldName,
            $this->getDoctrine()->getManager()->getClassMetadata($className)->getAssociationNames()
        );
        $allFieldNames   = array_flip($allFieldNames);
        if (count($excludeFields)) {
            foreach ($excludeFields as $item) {
                unset($allFieldNames[ $item ]);
            }
        }
        $allFieldNames = array_flip($allFieldNames);

        if (isset($sortBy)) {
            if ( ! in_array($sortBy, $allFieldNames)) {
                throw new BadRequestHttpException($this->get("translator")->trans('The sort field is illegal',["{{sortBy}}"=>$sortBy],"validators"));
            }
        }
        if (isset($sortType)) {
            if ( ! in_array(strtolower($sortType), $sortTypeArray)) {
                throw new BadRequestHttpException($this->get("translator")->trans('The sort type is invalid',[],"validators"));
            }
        }

        if (count($excludeFields)) {
            foreach ($excludeFields as $item) {
                array_push($allFieldNames, $item);
            }
        }
        $compareFields = array_diff_key($tempSearch, array_flip($allFieldNames));

        if (count($compareFields) > 0) {
            throw new BadRequestHttpException($this->get("translator")->trans("search fields are illegal",["{{searchField}}"=>implode(', ', (array_keys($compareFields)))],"validators")
            );
        }

        return $tempSearch;
    }

    protected function removeSortKeys($array, $extra=[]){
        unset($array["sortBy"]);
        unset($array["sortType"]);
        unset($array["count"]);
        unset($array["page"]);
        foreach ($extra as $item){
            unset($array[$item]);
        }
        return $array;
    }

    protected function dumpWithHeaders($data){
        dump($data);
        header("Access-Control-Allow-Origin:".$this->get("request_stack")->getCurrentRequest()->headers->get("origin"));
        header("Access-Control-Allow-Credentials: true");
        exit();
    }


    protected function countRequestParams(Request $request)
    {
        $count = 0;
        foreach ($request->request->all() as $item) {
            $count =(!is_array($item))?strlen($item) + $count: $count;
        }
        return $count;
    }
}