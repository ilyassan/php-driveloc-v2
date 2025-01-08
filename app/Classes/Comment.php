<?php
    class Comment extends BaseClass {

    private $id;
    private $content;
    private $is_deleted;
    private $created_at;
    private $article_id;
    private $client_id;

    public function __construct($id, $content, $is_deleted, $article_id, $client_id)
    {
        $this->id = $id;
        $this->content = $content;
        $this->is_deleted = $is_deleted;
        $this->article_id = $article_id;
        $this->client_id = $client_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getIsDeleted()
    {
        return $this->is_deleted;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getArticleId()
    {
        return $this->article_id;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function save()
    {
        $sql = "INSERT INTO comments (content, article_id, client_id)
                VALUES (:content, :article_id, :client_id)
                ";
        self::$db->query($sql);
        self::$db->bind(':content', $this->content);
        self::$db->bind(':article_id', $this->article_id);
        self::$db->bind(':client_id', $this->client_id);

        return self::$db->execute();
    }


    public static function getCommentsOfArticle(int $article_id) {
        $sql = "SELECT c.*, CONCAT(u.first_name, ' ', u.last_name) as author_name
                FROM comments c
                JOIN users u ON u.id = c.client_id
                WHERE c.article_id = :article_id
                ORDER BY c.created_at DESC";
                
        self::$db->query($sql);
        self::$db->bind(':article_id', $article_id);

        $results = self::$db->results();

        return $results;
    }
}