<?php

    class ArticlesPage extends BasePage
    {

        public function index($themeId)
        {
            $articlesPerPage = $_GET['per_page'] ?? 10; // Default is 10 per page

            $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
            $page = $_GET['page'] ?? 1;
            
            if (! is_numeric($page) || $page < 1 || ! is_numeric($articlesPerPage) || $articlesPerPage < 1) {
                redirect('themes/'. $themeId);
            }

            $theme = Theme::find($themeId);

            $articlesTotalCount = Article::countByFilter($themeId, $keyword);
            $articles = Article::paginate($themeId, $page, $articlesPerPage, $keyword);

            $this->render('/articles/index', compact('articles', 'articlesTotalCount', 'articlesPerPage', 'theme', 'keyword'));
        }

        public function show($id)
        {
            $article = Article::find($id);
            $comments = Comment::getCommentsOfArticle($id);
            $isFavorite = Favorite::isArticleFavorite($id, user()->getId());

            $this->render('/articles/show', compact('article', 'comments', 'isFavorite'));
        }

        public function create()
        {
            $themes = Theme::all();
            $tags = Tag::all();

            $this->render('/articles/create', compact('themes', 'tags'));
        }

        public function store()
        {
            $data = [
                'title' => isset($_POST['title']) ? trim(filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '',
                'theme_id' => isset($_POST['theme_id']) ? trim(filter_var($_POST['theme_id'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '',
                'content' => isset($_POST['content']) ? trim($_POST['content']) : '',
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