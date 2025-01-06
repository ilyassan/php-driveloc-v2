<?php

    class ArticlesPage extends BasePage
    {
        public function index()
        {
            $this->render('/articles/index');
        }
    }