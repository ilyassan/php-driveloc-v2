<?php
    class Article extends BaseClass {

    private $id;
    private $title;
    private $description;
    private $image_name;
    private $is_published;
    private $created_at;
    private $theme_id;
    private $client_id;

    public function __construct($id, $title, $description, $image_name, $is_published, $created_at, $theme_id, $client_id)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->image_name = $image_name;
        $this->is_published = $is_published;
        $this->created_at = $created_at;
        $this->theme_id = $theme_id;
        $this->client_id = $client_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getIsPublished()
    {
        return $this->is_published;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getThemeId()
    {
        return $this->theme_id;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function getImageName()
    {
        return $this->image_name;
    }


    public static function find(int $id) {
        $sql = "SELECT * FROM themes
                WHERE id = :id";
        self::$db->query($sql);
        self::$db->bind(':id', $id);
        self::$db->execute();

        $result = self::$db->single();
        return new self($result->id, $result->title, $result->description, $result->image_name,  $result->is_published, $result->created_at, $result->theme_id, $result->client_id);
    }


    public static function getArticlesOfTheme($themeId)
    {
        $sql = "SELECT a.*,
                COUNT(DISTINCT l.id) as likes_count,
                COUNT(DISTINCT d.id) as dislikes_count,
                COUNT(DISTINCT c.id) as comments_count
                FROM articles a
                LEFT JOIN likes l ON l.article_id = a.id
                LEFT JOIN dislikes d ON d.article_id = a.id
                LEFT JOIN comments c ON c.article_id = a.id
                WHERE a.theme_id = :theme_id
                GROUP BY a.id";
        
        self::$db->query($sql);
        self::$db->bind(':theme_id', $themeId);
        $results = self::$db->results();

        return $results;
    }
}