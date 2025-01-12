<?php
    class Article extends BaseClass {

    private $id;
    private $title;
    private $content;
    private $is_published;
    private $created_at;
    private $views;
    private $theme_id;
    private $client_id;

    public function __construct($id, $title, $content, $is_published, $created_at, $views, $theme_id, $client_id)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->is_published = $is_published;
        $this->created_at = $created_at;
        $this->views = $views;
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

    public function getViews()
    {
        return $this->views;
    }

    public function setIsPublished($is_published)
    {
        $this->is_published = $is_published;
    }

    public function setViews($views)
    {
        $this->views = $views;
    }

    public function save()
    {
        $sql = "INSERT INTO articles (title, content, theme_id, client_id)
                VALUES (:title, :content, :theme_id, :client_id)
                ";
        self::$db->query($sql);
        self::$db->bind(':title', $this->title);
        self::$db->bind(':content', $this->content);
        self::$db->bind(':theme_id', $this->theme_id);
        self::$db->bind(':client_id', $this->client_id);

        if (self::$db->execute()) {
            $this->id = self::$db->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    public function update()
    {
        $sql = "UPDATE articles
                SET title = :title, content = :content, is_published = :is_published, theme_id = :theme_id, client_id = :client_id, views = :views
                WHERE id = :id";

        self::$db->query($sql);
        self::$db->bind(':id', $this->id);
        self::$db->bind(':title', $this->title);
        self::$db->bind(':content', $this->content);
        self::$db->bind(':is_published', $this->is_published);
        self::$db->bind(':views', $this->views);
        self::$db->bind(':theme_id', $this->theme_id);
        self::$db->bind(':client_id', $this->client_id);

        if (self::$db->execute()) {
            $this->id = self::$db->lastInsertId();
            return true;
        } else {
            return false;
        }  
    }
    
    public function delete()
    {
        $sql = "DELETE FROM articles WHERE id = :id";
        self::$db->query($sql);
        self::$db->bind(':id', $this->id);
        self::$db->execute();
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
        $sql = "SELECT * FROM articles
                WHERE id = :id";

        self::$db->query($sql);
        self::$db->bind(':id', $id);
        self::$db->execute();

        $result = self::$db->single();
        return new self($result->id, $result->title, $result->content, $result->is_published, $result->created_at, $result->views, $result->theme_id, $result->client_id);
    }

    public static function findFullDetails(int $id) {
        $sql = "SELECT
                    a.*,
                    CONCAT(u.first_name, ' ', u.last_name) as author_name,
                    COUNT(DISTINCT l.id) as likes_count,
                    COUNT(DISTINCT d.id) as dislikes_count,
                    GROUP_CONCAT(t.name) as tags
                FROM articles a
                LEFT JOIN likes l ON l.article_id = a.id
                LEFT JOIN dislikes d ON d.article_id = a.id
                JOIN users u ON u.id = a.client_id
                LEFT JOIN articles_tags at ON at.article_id = a.id
                LEFT JOIN tags t ON t.id = at.tag_id
                WHERE a.id = :id";

        self::$db->query($sql);
        self::$db->bind(':id', $id);

        $result = self::$db->single();

        if ($result && $result->tags) {
            $result->tags = explode(',', $result->tags);
        }else{
            $result->tags = [];
        }
        
        return $result;
    }

    public static function all($keyword = '', $theme_id = '')
    {
        $sql = "SELECT * FROM articles WHERE 1=1";
    
        if (!empty($keyword)) {
            $sql .= " AND (title LIKE :keyword 
                     OR content LIKE :keyword)";
        }
        
        if (!empty($theme_id)) {
            $sql .= " AND theme_id = :theme_id";
        }
    
        self::$db->query($sql);
    
        if (!empty($keyword)) {
            self::$db->bind(':keyword', '%' . $keyword . '%');
        }
        
        if (!empty($theme_id)) {
            self::$db->bind(':theme_id', $theme_id);
        }
    
        $results = self::$db->results();
    
        return $results;
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

    public static function favoritesOfClient($client_id)
    {
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
                WHERE f.client_id IS NOT NULL
                GROUP BY a.id";

        
        self::$db->query($sql);
        self::$db->bind(':client_id', $client_id);
    
        $results = self::$db->results();
    
        return $results;
    }

    public static function pendings()
    {
        $sql = "SELECT
                    a.*,
                    CONCAT(u.first_name, ' ', u.last_name) as author_name
                FROM articles a
                JOIN users u ON u.id = a.client_id
                WHERE a.is_published = 0";
    
        self::$db->query($sql);
        $results = self::$db->results();
    
        return $results;
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
                    f.article_id as is_favorite,
                    GROUP_CONCAT(t.name) as tags
                FROM articles a
                LEFT JOIN likes l ON l.article_id = a.id
                LEFT JOIN likes li ON li.article_id = a.id AND li.client_id = :client_id
                LEFT JOIN dislikes d ON d.article_id = a.id
                LEFT JOIN dislikes di ON di.article_id = a.id AND di.client_id = :client_id
                LEFT JOIN comments c ON c.article_id = a.id
                LEFT JOIN favorites f ON f.article_id = a.id AND f.client_id = :client_id
                LEFT JOIN articles_tags at ON at.article_id = a.id
                LEFT JOIN tags t ON t.id = at.tag_id
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
        self::$db->bind(':client_id', isLoggedIn() ? user()->getId(): 0);
        self::$db->bind(':offset', $offset);
        self::$db->bind(':articles_per_page', $articlesPerPage);
        
        // Only bind search term if it's not empty
        if (!empty($searchTerm)) {
            self::$db->bind(':search_term', '%' . $searchTerm . '%');
        }
    
        $articles = self::$db->results();
    
        foreach ($articles as $key => $article) {
            if (!empty($article['tags'])) {
                $article['tags'] = explode(',', $article['tags']);
                $articles[$key] = $article;
            }else{
                $article['tags'] = [];
            }
        }


        return $articles;
    }
}