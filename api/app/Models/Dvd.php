<?php

declare(strict_types = 1);

namespace App\Models;

use PDO;

class Dvd extends Product
{
    public function __construct(
        private int $productTypeId,
        private string $sku,
        private string $name,
        private float $price,
        private int $size
    )
    {      
        parent::__construct(
            $productTypeId,
            $sku,
            $name,
            $price
        );
    }

    public function create(): true
    {
        $productTypeId = $this->getProductId();
        $sku = $this->getSku();
        $name = $this->getName();
        $price = $this->getPrice();
        $size = $this->getSize();

        try {
            self::$database->beginTransaction();

            $statement = self::$database->prepare(
                <<<SQL
                    INSERT INTO products
                        (
                            product_type_id,
                            sku,
                            name,
                            price,
                            size
                        )
                    VALUES
                        (
                            :productTypeId,
                            :sku,
                            :name,
                            :price,
                            :size
                        );
                SQL
            );

            $statement->bindValue(':productTypeId', $productTypeId, PDO::PARAM_INT);
            $statement->bindValue(':sku', $sku, PDO::PARAM_STR);
            $statement->bindValue(':name', $name, PDO::PARAM_STR);
            $statement->bindValue(':price', $price, PDO::PARAM_STR);
            $statement->bindValue(':size', $size, PDO::PARAM_INT);
            
            $statement->execute();
            
            static::$database->commit();

            return true;
        } catch (\Exception $exception) {
            static::$database->rollBack();
        }
        
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }
}