<?php
    class Tag extends BaseClass {

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
        $sql = "SELECT * FROM themes
                WHERE id = :id";
        self::$db->query($sql);
        self::$db->bind(':id', $id);
        self::$db->execute();

        $result = self::$db->single();
        return new self($result->id, $result->name, $result->description);
    }
    
    public static function all()
    {
        $sql = "SELECT * FROM tags";

        self::$db->query($sql);

        $results = self::$db->results();

        return $results;
    }

}