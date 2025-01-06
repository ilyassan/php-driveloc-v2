<?php
    class Reservation extends BaseClass {

    private $id;
    private $from_date;
    private $to_date;
    private $place_id;
    private $vehicle_id;
    private $client_id;

    public function __construct($id, $from_date, $to_date, $place_id, $vehicle_id, $client_id)
    {
        $this->id = $id;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->place_id = $place_id;
        $this->vehicle_id = $vehicle_id;
        $this->client_id = $client_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPickupDate()
    {
        return $this->from_date;
    }

    public function getReturnDate()
    {
        return $this->to_date;
    }

    public function getPlaceId()
    {
        return $this->place_id;
    }

    public function getVehicleId()
    {
        return $this->vehicle_id;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function save()
    {
        $sql = "INSERT INTO reservations (from_date, to_date, place_id, vehicle_id, client_id) 
                VALUES (:from_date, :to_date, :place_id, :vehicle_id, :client_id)";

        self::$db->query($sql);
        self::$db->bind(':from_date', $this->from_date);
        self::$db->bind(':to_date', $this->to_date);
        self::$db->bind(':place_id', $this->place_id);
        self::$db->bind(':vehicle_id', $this->vehicle_id);
        self::$db->bind(':client_id', $this->client_id);

        if (self::$db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function find(int $id) {
        $sql = "SELECT * FROM reservations
                WHERE id = :id";
        self::$db->query($sql);
        self::$db->bind(':id', $id);
        self::$db->execute();

        $result = self::$db->single();
        return new self($result->id, $result->from_date, $result->to_date, $result->place_id, $result->vehicle_id, $result->client_id);
    }
    
    public static function all()
    {
        $sql = "SELECT * FROM reservations";

        self::$db->query($sql);
        self::$db->execute();
    
        $results = self::$db->results();

        return $results;
    }

    public static function getReservationsOfClient($clientId, $filters = [])
    {
        $sql = "SELECT r.*,
                        v.name AS vehicle_name,
                        v.id AS vehicle_id,
                        c.name AS category_name,
                        v.price * (DATEDIFF(r.to_date, r.from_date) + 1) AS total_cost,
                        p.name as place_name,
                        ra.rate as rating
                FROM reservations r
                JOIN vehicles v ON r.vehicle_id = v.id
                LEFT JOIN ratings ra ON ra.vehicle_id = v.id AND ra.client_id = r.client_id AND is_deleted = 0
                JOIN categories c ON v.category_id = c.id
                JOIN places p ON r.place_id = p.id
                WHERE r.client_id = :client_id
                ";

        if (isset($filters['status']) && $filters['status'] !== 'All Status' && !empty($filters['status'])) {
            if ($filters['status'] === 'Upcoming') {
                $sql .= " AND r.from_date > NOW() ";
            } elseif ($filters['status'] === 'Active') {
                $sql .= " AND r.from_date <= NOW() AND r.to_date >= NOW() ";
            } elseif ($filters['status'] === 'Completed') {
                $sql .= " AND r.to_date < NOW() ";
            }
        }

        if (isset($filters['start_date']) && !empty($filters['start_date'])) {
            $sql .= " AND r.from_date >= :start_date ";
        }

        if (isset($filters['to_date']) && !empty($filters['to_date'])) {
            $sql .= " AND r.to_date <= :to_date ";
        }

        $sql .= " ORDER BY r.from_date DESC";

        self::$db->query($sql);
        self::$db->bind(':client_id', $clientId);

        if (isset($filters['start_date']) && !empty($filters['start_date'])) {
            self::$db->bind(':start_date', $filters['start_date']);
        }

        if (isset($filters['to_date']) && !empty($filters['to_date'])) {
            self::$db->bind(':to_date', $filters['to_date']);
        }

        if (!self::$db->execute()) {
            return false;
        }

        $results = self::$db->results();

        return $results;
    }


    public static function isVehicleReserved($vehicleId, $fromDate, $toDate)
    {
        $sql = "SELECT 1 FROM reservations
                WHERE vehicle_id = :vehicle_id
                  AND (
                  (:from_date BETWEEN from_date AND to_date)
                  OR
                  (:to_date BETWEEN from_date AND to_date)
                  OR
                  (from_date BETWEEN :from_date AND :to_date)
                  OR
                  (to_date BETWEEN :from_date AND :to_date)
                  );";

        self::$db->query($sql);
        self::$db->bind(':vehicle_id', $vehicleId);
        self::$db->bind(':from_date', $fromDate);
        self::$db->bind(':to_date', $toDate);

        self::$db->execute();

        return self::$db->rowCount() > 0;
    }


    public static function getReservationsCostBetween($startDate, $endDate)
    {
        $sql = "SELECT SUM(v.price * (DATEDIFF(r.to_date, r.from_date) + 1)) AS total_cost
                FROM reservations r
                JOIN vehicles v ON r.vehicle_id = v.id
                WHERE r.from_date >= :start_date
                AND r.from_date <= :end_date";
    
        self::$db->query($sql);
        self::$db->bind(':start_date', $startDate);
        self::$db->bind(':end_date', $endDate);
    
        if (!self::$db->execute()) {
            return false;
        }
    
        $result = self::$db->single();
    
        if ($result && isset($result->total_cost)) {
            return $result->total_cost;
        } else {
            return 0;
        }
    }

    public static function getReservationsCount($startDate, $endDate)
    {
        $sql = "SELECT COUNT(*) AS total_reservations
                FROM reservations
                WHERE from_date >= :start_date
                AND from_date <= :end_date";
    
        self::$db->query($sql);
        self::$db->bind(':start_date', $startDate);
        self::$db->bind(':end_date', $endDate);
    
        if (!self::$db->execute()) {
            return false;
        }
    
        $result = self::$db->single();
    
        if ($result && isset($result->total_reservations)) {
            return $result->total_reservations;
        } else {
            return 0;
        }
    }


    public static function getRecentReservations($limit)
    {
        $sql = "SELECT r.*, v.name AS vehicle_name
                FROM reservations r
                JOIN vehicles v ON r.vehicle_id = v.id
                ORDER BY r.created_at DESC
                LIMIT :limit";
        
        self::$db->query($sql);
        self::$db->bind(':limit', $limit);

        $results = self::$db->results();

        return $results;
    }

}