<?php
    class Theme extends BaseClass {

    private $id;
    private $name;
    private $description;

    public function __construct($id, $name, $description)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
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
        $sql = "SELECT t.*, COUNT(a.id) as articles_count
                FROM themes t
                JOIN articles a ON a.theme_id = t.id
                GROUP BY t.id";

        self::$db->query($sql);

        $results = self::$db->results();

        return $results;
    }

}