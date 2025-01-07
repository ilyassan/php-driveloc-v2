<?php

    class ArticlesPage extends BasePage
    {
        public function index($id)
        {
            $articles = Article::getArticlesOfTheme($id);
            
            $this->render('/articles/index', compact('articles'));
        }

        public function show($id)
        {
            $this->render('/articles/show');
        }

        public function create()
        {
            $this->render('/articles/create');
        }
    }