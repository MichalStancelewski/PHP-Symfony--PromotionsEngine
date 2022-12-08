<?php

namespace App\Filter\Modifier;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class EvenItemsMultiplier implements PriceModifierInterface
{

    public function modify(int $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
    {
        if ($quantity < 2) {
            return $price * $quantity;
        } else if ($quantity % 2 == 0) {
            return $price * $quantity * $promotion->getAdjustment();
        } else {
            return ($price * ($quantity - 1) * $promotion->getAdjustment()) + $price;
        }
    }
}