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
    }