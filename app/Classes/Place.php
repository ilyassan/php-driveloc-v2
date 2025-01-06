<?php
    class Place extends BaseClass {

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

    public static function find(int $id) {
        $sql = "SELECT * FROM place
                WHERE id = :id";
        self::$db->query($sql);
        self::$db->bind(':id', $id);
        self::$db->execute();

        $result = self::$db->single();
        return new self($result->id, $result->name);
    }
    
    public static function all()
    {
        $sql = "SELECT * FROM places";

        self::$db->query($sql);
        self::$db->execute();
    
        $result = self::$db->results();

        $places = [];
        foreach ($result as $category) {
            $places[] = new self($category["id"], $category["name"]);
        }
    
        return $places;
    }

}