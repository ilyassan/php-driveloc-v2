<?php

    class PendingArticlesPage extends BasePage
    {

        public function index()
        {
            $articles = Article::pendings();

            $this->render('/pendingArticles/index', compact('articles'));
        }

        public function show($id)
        {
            $this->render('/pendingArticles/show');
        }

        public function publishArticle()
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id = $_POST['article_id'];
            
            $article = Article::find($id);
            $article->setIsPublished(1);
            $article->update();

            flash("success", "Article published successfully.");
            redirect("articles/pending");
        }

        public function refuseArticle()
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id = $_POST['article_id'];
            
            $article = Article::find($id);
            $article->delete();

            flash("success", "Article deleted successfully.");
            redirect("articles/pending");
        }
    }