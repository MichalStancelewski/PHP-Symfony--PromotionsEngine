<?php

namespace App\DTO;

use App\Entity\Product;

interface PromotionEnquiryInterface
{
    public function setPromotionId(int $promotionId);

    public function setPromotionName(string $promotionName);

    public function getProduct(): ?Product;
}