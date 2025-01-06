<?php

    class PendingArticlesPage extends BasePage
    {

        public function index()
        {
            $this->render('/pendingArticles/index');
        }

        public function show($id)
        {
            $this->render('/pendingArticles/show');
        }
    }