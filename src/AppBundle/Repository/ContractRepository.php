<?php


namespace AppBundle\Repository;


use AppBundle\Entity\Advertising;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\AST\Functions\ConcatFunction;

class ContractRepository extends EntityRepository {

    public function findContracts($search,$userId)
    {
        $query = $this->createQueryBuilder('contract')
        ->leftJoin('contract.owner','owner')
        ->leftJoin('contract.serviceItems','serviceItems')
        ->leftJoin('contract.shareItems','shareItems')
        ->leftJoin('contract.posts','posts')
        ;
        if($userId!=1){
            $query->andWhere("contract.owner =:ownerId")
                ->setParameter("ownerId", $userId);
        }
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

    public function findItemContracts($search,$userId)
    {
        $query = $this->createQueryBuilder('contract')
            ->leftJoin('contract.owner','owner')
            ->leftJoin('contract.serviceItems','serviceItems')
            ->leftJoin('contract.shareItems','shareItems')
            ->Join('contract.posts','posts')
        ;
        if($userId!=1){
            $query->andWhere("contract.owner =:ownerId")
                ->setParameter("ownerId", $userId);
        }
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