<?php

    class ArticlesAdminPage extends BasePage
    {
        public function index()
        {
            $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
            $theme_id = isset($_GET['theme_id']) ? trim($_GET['theme_id']) : null;

            $articles = Article::all($keyword, $theme_id);
            $themes = Theme::all();

            $this->render('/articles/index', compact('articles', 'themes'));
        }

        public function show($id)
        {
            $article = Article::findFullDetails($id);
            $comments = Comment::getCommentsOfArticle($id);
            $isFavorite = Favorite::isArticleFavorite($id, user()->getId());
            $isLiked = Like::isArticleLikedByUser($id, user()->getId());
            $isDisliked = Dislike::isArticleDislikedByUser($id, user()->getId());

            $this->render('/articles/show', compact('article', 'comments', 'isFavorite', 'isLiked', 'isDisliked'));
        }

        public function pending()
        {
            $this->render('/articles/pending');
        }

        public function delete()
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id = $_POST['article_id'];
            
            $article = Article::find($id);
            $article->delete();

            flash("success", "Article removed successfully.");
            redirect("articles");
        }
    }