<?php
    class Theme extends BaseClass {

    private $id;
    private $name;
    private $description;
    private $image_name;

    public function __construct($id, $name, $description, $image_name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->image_name = $image_name;
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

    public function getImageName()
    {
        return $this->image_name;
    }

    public function getImagePath()
    {
        return ASSETSROOT . 'images/themes/' . $this->image_name;
    }

    
    public function save()
    {
        $sql = "INSERT INTO themes (name, description, image_name)
                VALUES (:title, :description, :image_name)
                ";
        self::$db->query($sql);
        self::$db->bind(':title', $this->name);
        self::$db->bind(':description', $this->description);
        self::$db->bind(':image_name', $this->image_name);

        return self::$db->execute();
    }


    public static function find(int $id) {
        $sql = "SELECT * FROM themes
                WHERE id = :id";
        self::$db->query($sql);
        self::$db->bind(':id', $id);
        self::$db->execute();

        $result = self::$db->single();
        return new self($result->id, $result->name, $result->description, $result->image_name);
    }
    
    public static function all()
    {
        $sql = "SELECT t.*, COUNT(a.id) as articles_count
                FROM themes t
                LEFT JOIN articles a ON a.theme_id = t.id
                GROUP BY t.id";

        self::$db->query($sql);

        $results = self::$db->results();

        return $results;
    }

}