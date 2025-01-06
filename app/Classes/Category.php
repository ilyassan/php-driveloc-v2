<?php
    class Category extends BaseClass {

    private $id;
    private $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function save()
    {
        $sql = "INSERT INTO categories (name) VALUES (:name)";
        self::$db->query($sql);
        self::$db->bind(':name', $this->name);
        self::$db->execute();
    }

    public function delete()
    {
        $sql = "DELETE FROM categories WHERE id = :id";
        self::$db->query($sql);
        self::$db->bind(':id', $this->id);
        self::$db->execute();
    }


    public static function find(int $id) {
        $sql = "SELECT * FROM categories
                WHERE id = :id";
        self::$db->query($sql);
        self::$db->bind(':id', $id);
        self::$db->execute();

        $result = self::$db->single();
        return new self($result->id, $result->name);
    }
    
    public static function all()
    {
        $sql = "SELECT * FROM categories";

        self::$db->query($sql);
        self::$db->execute();
    
        $result = self::$db->results();

        $categories = [];
        foreach ($result as $category) {
            $categories[] = new self($category["id"], $category["name"]);
        }
    
        return $categories;
    }

    public static function popularCategories()
    {
        $sql = "SELECT c.name as category, COUNT(r.id) as reservations_count
                FROM categories c
                JOIN vehicles v ON v.category_id = c.id
                JOIN reservations r ON r.vehicle_id = v.id
                GROUP BY c.id
                ORDER BY reservations_count DESC
                LIMIT 5";

        self::$db->query($sql);
        self::$db->execute();

        $results = self::$db->results();
        return $results;
    }

}