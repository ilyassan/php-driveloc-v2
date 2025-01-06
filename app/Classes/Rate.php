<?php
    class Rate extends BaseClass {

    private $id;
    private $rate;
    private $client_id;
    private $vehicle_id;

    public function __construct($id, $rate, $client_id, $vehicle_id)
    {
        $this->id = $id;
        $this->rate = $rate;
        $this->client_id = $client_id;
        $this->vehicle_id = $vehicle_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function getVehicleId()
    {
        return $this->vehicle_id;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;
    }


    public function save()
    {
        $sql = "INSERT INTO ratings (rate, client_id, vehicle_id) 
                VALUES (:rate, :client_id, :vehicle_id)";

        self::$db->query($sql);
        self::$db->bind(':rate', $this->rate);
        self::$db->bind(':client_id', $this->client_id);
        self::$db->bind(':vehicle_id', $this->vehicle_id);
        self::$db->execute();
    }

    public function update()
    {
        $sql = "UPDATE ratings
                SET rate = :rate, is_deleted = 0
                WHERE id = :id";

        self::$db->query($sql);
        self::$db->bind(':rate', $this->rate);
        self::$db->bind(':id', $this->id);
        self::$db->execute();
    }

    public function delete()
    {
        $sql = "UPDATE ratings
                SET is_deleted = 1
                WHERE id = :id";

        self::$db->query($sql);
        self::$db->bind(':id', $this->id);
        self::$db->execute();
    }


    public static function getRateOfClient($client_id, $vehicle_id)
    {
        $sql = "SELECT * FROM ratings
                WHERE client_id = :client_id AND vehicle_id = :vehicle_id";

        self::$db->query($sql);
        self::$db->bind(':client_id', $client_id);
        self::$db->bind(':vehicle_id', $vehicle_id);
        self::$db->execute();

        $result = self::$db->single();
        return $result ? new self($result->id, $result->rate, $result->client_id, $result->vehicle_id) : null;
    }

    public static function find(int $id) {
        $sql = "SELECT * FROM ratings
                WHERE id = :id";
        self::$db->query($sql);
        self::$db->bind(':id', $id);
        self::$db->execute();

        $result = self::$db->single();
        return $result ? new self($result->id, $result->rate, $result->client_id, $result->vehicle_id) : null;
    }

    public static function getRecentRates($limit)
    {
        $sql = "SELECT r.*, v.name as vehicle_name
                FROM ratings r
                JOIN vehicles v ON r.vehicle_id = v.id
                ORDER BY r.created_at DESC
                LIMIT :limit";

        self::$db->query($sql);
        self::$db->bind(':limit', $limit);

        $results = self::$db->results();

        return $results;
    }

    public static function avg()
    {
        $sql = "SELECT AVG(rate) as avg_rate FROM ratings";
        self::$db->query($sql);
        self::$db->execute();

        $result = self::$db->single();
        return $result->avg_rate;
    }

}