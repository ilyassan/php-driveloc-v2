<?php

    class ThemesPage extends BasePage
    {
        public function index()
        {
            $themes = Theme::all();

            $this->render('/themes/index', compact('themes'));
        }
    }