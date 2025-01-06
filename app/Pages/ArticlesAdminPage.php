<?php

    class ArticlesAdminPage extends BasePage
    {
        public function index()
        {
            $this->render('/articles/index');
        }
    }