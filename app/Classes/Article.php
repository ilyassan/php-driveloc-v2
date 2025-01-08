<?php
    class Article extends BaseClass {

    private $id;
    private $title;
    private $content;
    private $image_name;
    private $is_published;
    private $created_at;
    private $theme_id;
    private $client_id;

    public function __construct($id, $title, $content, $image_name, $is_published, $created_at, $theme_id, $client_id)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
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

    public function getContent()
    {
        return $this->content;
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

    public function save()
    {
        $sql = "INSERT INTO articles (title, content, image_name, theme_id, client_id)
                VALUES (:title, :content, :image_name, :theme_id, :client_id)
                ";
        self::$db->query($sql);
        self::$db->bind(':title', $this->title);
        self::$db->bind(':content', $this->content);
        self::$db->bind(':image_name', $this->image_name);
        self::$db->bind(':theme_id', $this->theme_id);
        self::$db->bind(':client_id', $this->client_id);

        if (self::$db->execute()) {
            $this->id = self::$db->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public function attachTags($ids)
    {
        $sql = "INSERT INTO articles_tags (article_id, tag_id) VALUES ";
        $values = [];
        $params = [];
        foreach ($ids as $index => $tag_id) {
            $values[] = "(:article_id_{$index}, :tag_id_{$index})";
            $params[":article_id_{$index}"] = $this->id;
            $params[":tag_id_{$index}"] = $tag_id;
        }
        $sql .= implode(", ", $values);
        self::$db->query($sql);
        foreach ($params as $key => $value) {
            self::$db->bind($key, $value);
        }

        return self::$db->execute();
    }


    public static function find(int $id) {
        $sql = "SELECT
                    a.*,
                    CONCAT(u.first_name, ' ', u.last_name) as author_name,
                    COUNT(DISTINCT l.id) as likes_count,
                    COUNT(DISTINCT d.id) as dislikes_count
                FROM articles a
                LEFT JOIN likes l ON l.article_id = a.id
                LEFT JOIN dislikes d ON d.article_id = a.id
                JOIN users u ON u.id = a.client_id
                WHERE a.id = :id";

        self::$db->query($sql);
        self::$db->bind(':id', $id);
        self::$db->execute();

        $result = self::$db->single();
        return $result;
    }


    public static function countByFilter($themeId, $keyword)
    {
        $sql = "SELECT COUNT(a.id) as count
                FROM articles a
                WHERE a.theme_id = :theme_id
                AND (a.title LIKE :search_term 
                OR a.content LIKE :search_term)";

        self::$db->query($sql);
        self::$db->bind(':theme_id', $themeId);
        self::$db->bind(':search_term', '%' . $keyword . '%');
        $result = self::$db->single();

        return $result->count; 
    }


    public static function paginate($themeId, int $page, int $articlesPerPage, $searchTerm = '')
    {
        $offset = ($page - 1) * $articlesPerPage;
        
        // Base SQL query
        $sql = "SELECT
                    a.*,
                    COUNT(DISTINCT l.id) as likes_count,
                    COUNT(DISTINCT d.id) as dislikes_count,
                    COUNT(DISTINCT c.id) as comments_count,
                    li.article_id as is_liked,
                    di.article_id as is_disliked,
                    f.article_id as is_favorite
                FROM articles a
                LEFT JOIN likes l ON l.article_id = a.id
                LEFT JOIN likes li ON li.article_id = a.id AND li.client_id = :client_id
                LEFT JOIN dislikes d ON d.article_id = a.id
                LEFT JOIN dislikes di ON di.article_id = a.id AND di.client_id = :client_id
                LEFT JOIN comments c ON c.article_id = a.id
                LEFT JOIN favorites f ON f.article_id = a.id AND f.client_id = :client_id
                WHERE a.theme_id = :theme_id";
        
        // Add search condition only if search term is not empty
        if (!empty($searchTerm)) {
            $sql .= " AND (a.title LIKE :search_term 
                     OR a.content LIKE :search_term)";
        }
        
        $sql .= " GROUP BY a.id
                  LIMIT :offset, :articles_per_page";
        
        self::$db->query($sql);
        self::$db->bind(':theme_id', $themeId);
        self::$db->bind(':client_id', user()->getId());
        self::$db->bind(':offset', $offset);
        self::$db->bind(':articles_per_page', $articlesPerPage);
        
        // Only bind search term if it's not empty
        if (!empty($searchTerm)) {
            self::$db->bind(':search_term', '%' . $searchTerm . '%');
        }
    
        $results = self::$db->results();
    
        return $results;
    }
}