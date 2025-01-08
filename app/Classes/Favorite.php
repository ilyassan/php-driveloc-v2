<?php
    class Favorite extends BaseClass {

    private $id;
    private $article_id;
    private $client_id;

    public function __construct($id, $article_id, $client_id)
    {
        $this->id = $id;
        $this->article_id = $article_id;
        $this->client_id = $client_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getArticleId()
    {
        return $this->article_id;
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public static function isArticleFavorite($article_id, $client_id)
    {
        $sql = "SELECT count(*) AS count
                FROM favorites f
                WHERE f.article_id = :article_id AND f.client_id = :client_id";
        
        self::$db->query($sql);
        self::$db->bind(':article_id', $article_id);
        self::$db->bind(':client_id', $client_id);

        $result = self::$db->single();

        return $result->count > 0;
    }
}