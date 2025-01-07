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
            $themes = Theme::all();
            $tags = Tag::all();

            $this->render('/articles/create', compact('themes', 'tags'));
        }

        public function store()
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'title' => trim($_POST['title']),
                'theme_id' => trim($_POST['theme_id']),
                'content' => trim($_POST['content']),
                'tag_ids' => isset($_POST['tag_ids']) ? $_POST['tag_ids'] : []
            ];
    
            $errors = [
                'title_err' => '',
                'theme_id_err' => '',
                'content_err' => '',
                'tag_ids_err' => '',
                'general_err' => '',
            ];

            // Validate the Inputs Data
            if (empty($data['title'])) {
                $errors['title_err'] = 'Please enter the article title.';
            }
    
            if (empty($data['theme_id']) || !Article::find($data['theme_id'])) {
                $errors['theme_id_err'] = 'Please select the vehicle theme.';
            }

            if (empty($data['content'])) {
                $errors['content_err'] = 'Please enter the article content.';
            }
    
            if (count($data['tag_ids']) < 1) {
                $errors['tag_ids_err'] = 'Please select at least 1 tag.';
            }
    
            // Check for errors and store into Database
            if (empty(array_filter($errors))) {

                $article = new Article(null, $data['title'], $data["content"], null, null, null, $data["theme_id"], user()->getId());

                if ($article->save() && $article->attachTags($data['tag_ids'])) {
                    flash("success", "You will be notify when your article has been published!");
                    redirect('themes');
                } else {
                    $errors['general_err'] = 'Something went wrong while adding the article.';
                }
            }
    
            flash("error", array_first_not_null_value($errors));
            redirect('articles/create');
        }
    }