<?php

    class FavoritesPage extends BasePage
    {
        public function index()
        {
            $articles = Article::favoritesOfClient(user()->getId());

            $this->render('/favorites/index', compact('articles'));
        }
    }