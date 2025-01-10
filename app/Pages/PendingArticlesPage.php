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
    }