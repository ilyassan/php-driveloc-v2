<?php

    class ArticlesPage extends BasePage
    {
        public function index($id)
        {
            $this->render('/articles/index');
        }

        public function show($id)
        {
            $this->render('/articles/show');
        }
    }