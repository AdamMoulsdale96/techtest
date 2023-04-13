<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    private $price;
    private $delivery_address;
    private $customer_id;

    public function getPrice()
    {
        return $this->price;
    }

    public function getDeliveryAddress()
    {
        return $this->delivery_address;
    }

    public function getCustomerId()
    {
        return $this->customer_id;
    }

    public function setCustomerId($customer_id)
    {
        $this->customer_id = $customer_id;
    }

    public function setDeliveryAddress($delivery_address)
    {
        $this->delivery_address = $delivery_address;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }
}
