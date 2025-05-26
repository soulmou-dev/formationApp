<?php
namespace App\Tests;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use PHPUnit\Framework\TestCase;
use App\Service\PaginatorService;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;


class PaginatorServiceTest extends TestCase
{

    public function testPaginate(){

        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $paginator = new PaginatorService($mockEntityManager);

        // paginate(QueryBuilder $queryBuilder, string $countParam, Request $request, int $perPage = 10):array
        $queryBuilderMock = $this->createMock(QueryBuilder::class);
       
        $queryMockForCount= $this->createMock(Query::class);
        $queryMockForCount->method('getSingleScalarResult')->willReturn(25);

       
        $queryMockForResults= $this->createMock(Query::class);
        $queryMockForResults->method('getResult')->willReturn(['item1','item2']);

        $queryBuilderMock->method('select')->willReturn($queryBuilderMock);
        $queryBuilderMock->method('setFirstResult')->willReturn($queryBuilderMock);
        $queryBuilderMock->method('setMaxResults')->willReturn($queryBuilderMock);
        $queryBuilderMock->method('getQuery')->willReturnOnConsecutiveCalls(
            $queryMockForCount,
            $queryMockForResults
        );

        $attributesMock = $this->createMock(ParameterBag::class);
        $attributesMock->method('getInt')->with('page')->willReturn(2);

        $requestMock = $this->createMock(Request::class);
        $requestMock->attributes= $attributesMock;

        $result = $paginator->paginate($queryBuilderMock, 'e.id', $requestMock, 10);

        // Assert
        $this->assertIsArray($result);
        $this->assertSame(25, $result['totalItems']);
        $this->assertSame(10, $result['perPage']);
        $this->assertSame(2, $result['page']);
        $this->assertSame(3, $result['totalPages']);
        $this->assertSame(['item1', 'item2'], $result['results']);

       






    }

}