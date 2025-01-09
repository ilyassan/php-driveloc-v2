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