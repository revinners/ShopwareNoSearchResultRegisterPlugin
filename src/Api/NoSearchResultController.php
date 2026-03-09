<?php declare(strict_types=1);
namespace Revinners\NoSearchResultRegister\Api;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
#[Route(defaults: ['_routeScope' => ['api']])]
class NoSearchResultController extends AbstractController
{
    public function __construct(private readonly EntityRepository $noSearchResultRepository)
    {
    }
    #[Route(
        path: '/api/revinners/no-search-results',
        name: 'api.revinners.no_search_results.list',
        methods: ['GET']
    )]
    public function list(Request $request, Context $context): JsonResponse
    {
        $criteria = new Criteria();
        $criteria->addSorting(new FieldSorting('count', FieldSorting::DESCENDING));
        $criteria->setLimit((int) $request->query->get('limit', 25));
        $criteria->setOffset((int) $request->query->get('offset', 0));
        $result = $this->noSearchResultRepository->search($criteria, $context);
        $items = [];
        foreach ($result->getEntities() as $entity) {
            $items[] = [
                'id'              => $entity->getId(),
                'phrase'          => $entity->getPhrase(),
                'count'           => $entity->getCount(),
                'firstSearchedAt' => $entity->getFirstSearchedAt()->format(\DateTimeInterface::ATOM),
                'lastSearchedAt'  => $entity->getLastSearchedAt()->format(\DateTimeInterface::ATOM),
            ];
        }
        return new JsonResponse([
            'total' => $result->getTotal(),
            'data'  => $items,
        ]);
    }
}
