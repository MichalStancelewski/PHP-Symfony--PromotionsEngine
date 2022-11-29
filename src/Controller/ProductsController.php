<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController
{
    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
    public function lowestPrice(Request $request, int $id): Response
    {
        if ($request->headers->has('force_fail')) {
            return new JsonResponse([
                "error" => "Promotions Engine failure message"
            ], $request->headers->get('force_fail'));
        }

        // 1. Deserialize JSON into DTO
        // 2. Pass enquiry into promotions filter. The appropriate promotion will be applied
        // 3. Return modified enquiry

        return new JsonResponse([
            "quantity" => 5,
            "request_location" => "UK",
            "voucher_code" => "OU812",
            "request_date" => "2022-04-04",
            "product_id" => $id,
            "price" => 100,
            "discounted_price" => 50,
            "promotion_id" => 3,
            "promotion_name" => "Black Friday"
        ], 200);
    }
}