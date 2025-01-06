<?php

    class ArticlesAdminPage extends BasePage
    {
        public function index()
        {
            $this->render('/articles/index');
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