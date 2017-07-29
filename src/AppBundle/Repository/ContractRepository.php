<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Advertising;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\AST\Functions\ConcatFunction;

class ContractRepository extends EntityRepository {

    public function findContracts($search)
    {
        $query = $this->createQueryBuilder('contract');
        foreach ($search as $key => $value) {
            if($key!='sortBy' && $key!='sortType' && $key !='page' && $key!='count'){
                if (is_array($value)) {
                    if (isset($value['from']) && isset($value['to'])) {
                        $query->andHaving("contract.contractDate BETWEEN :from AND :to")

                            ->setParameter('from', $value['from'])
                            ->setParameter('to', $value['to']);
                    } elseif (isset($value['from'])) {

                        $query->andHaving("contract.contractDate >= :from")
                            ->setParameter("from", $value['from']);
                    } elseif (isset($value['to'])) {
                        $query->andHaving("contract.contractDate <= :to")
                            ->setParameter("to", $value['to']);
                    }
                } else {
                    switch ($key){
                        default:
                            $selectAlias="contract.$key";
                    }
                    $query->andWhere("$selectAlias LIKE :contract{$key}")
                        ->setParameter("contract{$key}", '%' . $value . '%');
                }
            }
        }
        return $query->getQuery()->execute();
    }


}