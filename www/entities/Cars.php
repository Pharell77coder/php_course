<?php

class Car {
    private int $id;
    private string $model;
    private string $brand;
    private float $price;
    private string $build_at;
    private string $plate;
    private PDO $db;
    public function __construct($db, $id)
    {
        $query = $db->prepare("SELECT * FROM cars WHERE id = :id");
        $query->bindValue(":id", $id, PDO::PARAM_INT);
        $query->execute();

        $car = $query->fetchObject();

        if(!$car) {
            throw new InvalidArgumentException("Pas de car avec cet id");
        }

        $this->id = $id;
        $this->model = $car->model;
        $this->brand = $car->brand;
        $this->price = $car->price;
        $this->build_at = $car->build_at;
        $this->plate = $car->plate;
        $this->db = $db;
    }

    public static function create($db, $model, $brand, $price, $build_at, $plate): Car
    {
        $query = $db->prepare("INSERT INTO cars (model, brand, price, build_at, plate) VALUES (:model, :brand, :price, :build_at, :plate)");
        $query->bindValue(':model', $model);
        $query->bindValue(':brand', $brand);
        $query->bindValue(':price', $price, PDO::PARAM_STR);
        $query->bindValue(':build_at', $build_at, PDO::PARAM_STR);
        $query->bindValue(':plate', $plate);
        $query->execute();

        return new Car($db, $db->lastInsertId());
    }

    public function delete(): void
    {
      
        $query = $this->db->prepare("DELETE FROM cars WHERE id = :id");
        $query->bindValue(":id", $this->id, PDO::PARAM_INT);
        $query->execute();
    }

    public function update(): void
    {
        $query = $this->db->prepare("UPDATE cars SET model = :model, brand = :brand, price = :price, plate = :plate WHERE id = :id");
        $query->bindValue(':model', $this->model);
        $query->bindValue(':brand', $this->brand);
        $query->bindValue(':price', $this->price);
        $query->bindValue(':build_at', $this->build_at);
        $query->bindValue(":plate", $this->plate);
        $query->bindValue(":id", $this->id);
        $query->execute();

    }
}
?>