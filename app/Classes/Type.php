<?php
    class Type extends BaseClass {

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
        $sql = "SELECT * FROM types
                WHERE id = :id";
        self::$db->query($sql);
        self::$db->bind(':id', $id);
        self::$db->execute();

        $result = self::$db->single();
        return new self($result->id, $result->name);
    }
    
    public static function all()
    {
        $sql = "SELECT * FROM types";

        self::$db->query($sql);
        self::$db->execute();
    
        $result = self::$db->results();

        $types = [];
        foreach ($result as $type) {
            $types[] = new self($type["id"], $type["name"]);
        }
    
        return $types;
    }

}