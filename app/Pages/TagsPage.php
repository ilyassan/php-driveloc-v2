<?php

    class TagsPage extends BasePage
    {
        public function index()
        {
            $tags = Tag::all();

            $this->render('/tags/index', compact('tags'));
        }
    }