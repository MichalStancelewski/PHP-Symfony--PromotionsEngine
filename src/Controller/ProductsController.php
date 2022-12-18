<?php

namespace App\Controller;

use App\Cache\PromotionCache;
use App\DTO\LowestPriceEnquiry;
use App\Filter\PromotionsFilterInterface;
use App\Repository\AuthKeyRepository;
use App\Repository\ProductRepository;
use App\Service\Serializer\DTOSerializer;
use App\Service\ServiceException;
use App\Service\ServiceExceptionData;
use App\Service\ValidationExceptionData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ProductsController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository      $repository,
        private readonly EntityManagerInterface $entityManager,
        private AuthKeyRepository               $authKeyRepository
    )
    {
    }

    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
    public function lowestPrice(Request $request, int $id, DTOSerializer $serializer, PromotionsFilterInterface $promotionsFilter, PromotionCache $promotionCache): Response
    {
        $authorization = $request->headers->get('Authorization');
        $authKey = $this->authKeyRepository->find(1)->getKeyName();

        if (!($authorization == $authKey)) {
            $accessExceptionData = new ServiceExceptionData(403, 'Access denied.');
            throw new ServiceException($accessExceptionData);
        }

        /** @var LowestPriceEnquiry $lowestPriceEnquiry */
        $lowestPriceEnquiry = $serializer->deserialize(
            $request->getContent(), LowestPriceEnquiry::class, 'json'
        );


        $product = $this->repository->findOrFail($id);

        $lowestPriceEnquiry->setProduct($product);

        $promotions = $promotionCache->findValidForProduct($product, $lowestPriceEnquiry->getRequestDate());

        $modifiedEnquiry = $promotionsFilter->apply($lowestPriceEnquiry, ...$promotions);

        $responseContent = $serializer->serialize($modifiedEnquiry, 'json');

        return new JsonResponse(data: $responseContent, status: Response::HTTP_OK, json: true);

    }
}