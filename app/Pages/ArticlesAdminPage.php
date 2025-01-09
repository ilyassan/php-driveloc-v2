<?php

    class ArticlesAdminPage extends BasePage
    {
        public function index()
        {
            $articles = Article::all();

            $this->render('/articles/index', compact('articles'));
        }

        public function show($id)
        {
            $this->render('/articles/show');
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