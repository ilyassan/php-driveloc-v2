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
            $article = Article::findFullDetails($id);
            $comments = Comment::getCommentsOfArticle($id);
            $isFavorite = Favorite::isArticleFavorite($id, user()->getId());
            $isLiked = Like::isArticleLikedByUser($id, user()->getId());
            $isDisliked = Dislike::isArticleDislikedByUser($id, user()->getId());

            $this->render('/articles/show', compact('article', 'comments', 'isFavorite', 'isLiked', 'isDisliked'));
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

        public function createComment()
        {
            $data = [
                'comment' => isset($_POST['comment']) ? trim(filter_var($_POST['comment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : '',
                'theme_id' => $_POST["theme_id"]
            ];

            $errors = [
                'comment_err' => '',
                'theme_id_err' => '' 
            ];

            if (empty($data['comment'])) {
                $errors['comment_err'] = "Cannot add a comment without any content.";
            }

            if (empty($data['theme_id']) || ! Article::find($data['theme_id'])) {
                $errors['theme_id_err'] = "The article not found.";
            }

            if (empty(array_filter($errors))) {

                $comment = new Comment(null, $data["comment"], null, null, $data["theme_id"], user()->getId());

                if ($comment->save()) {
                    flash("success", "Your comment has been added successfully!");
                    redirect('articles/'. $data["theme_id"]);
                } else {
                    $errors['general_err'] = 'Something went wrong while adding your comment.';
                }
            }
    
            flash("error", array_first_not_null_value($errors));
            redirect('articles/'. $data["theme_id"]);
        }

        public function updateComment()
        {

            $data = [
                'comment' => $_POST['comment'] ,
                'comment_id' => $_POST['comment_id'] ?? null,
                'article_id' => $_POST['article_id'] ?? null
            ];

            $errors = [
                'comment_err' => '',
                'comment_id_err' => '',
                'authorization_err' => '',
                'general_err' => ''
            ];

            if (empty($data['comment'])) {
                $errors['comment_err'] = "Empty comment content.";
            }

            $comment = Comment::find($data['comment_id']);
            if (!$comment) {
                $errors['comment_id_err'] = "Comment not found.";
            }

            // Check if user is authorized to delete the comment
            if ($comment && user()->isClient() && $comment->getClientId() !== user()->getId()) {
                $errors['authorization_err'] = "You are not authorized to modify this comment.";
            }

            if (empty(array_filter($errors))) {

                $comment->setContent($data['comment']);

                if ($comment->update()) {
                    flash("success", "Comment updated successfully!");
                } else {
                    flash("error", "Something went wrong while updating the comment.");
                }
            } else {
                flash("error", array_first_not_null_value($errors));
            }

            redirect('articles/' . $data['article_id']);
        }

        public function deleteComment()
        {

            $data = [
                'comment_id' => $_POST['comment_id'] ?? null,
                'article_id' => $_POST['article_id'] ?? null
            ];

            $errors = [
                'comment_id_err' => '',
                'authorization_err' => '',
                'general_err' => ''
            ];

            $comment = Comment::find($data['comment_id']);
            if (!$comment) {
                $errors['comment_id_err'] = "Comment not found.";
            }

            // Check if user is authorized to delete the comment
            if ($comment && user()->isClient() && $comment->getClientId() !== user()->getId()) {
                $errors['authorization_err'] = "You are not authorized to delete this comment.";
            }

            if (empty(array_filter($errors))) {
                if ($comment->delete()) {
                    flash("success", "Comment deleted successfully!");
                } else {
                    flash("error", "Something went wrong while deleting the comment.");
                }
            } else {
                flash("error", array_first_not_null_value($errors));
            }

            redirect('articles/' . $data['article_id']);
        }


        public function like()
        {
            $data = [
                'article_id' => $_POST["article_id"]
            ];

            $errors = [
                'article_id_err' => '' 
            ];

            if (empty($data['article_id']) || ! Article::find($data['article_id'])) {
                $errors['article_id_err'] = "The article not found.";
            }

            if (Like::find($data['article_id'], user()->getId())) {
                Like::remove($data["article_id"], user()->getId());
                redirect('articles/'. $data["article_id"]);
                return;
            }

            if (empty(array_filter($errors))) {

                Dislike::remove($data["article_id"], user()->getId());
                $like = new Like(null, $data["article_id"], user()->getId());
                $like->save();
            }
    
            flash("error", array_first_not_null_value($errors));
            redirect('articles/'. $data["article_id"]);
        }


        public function dislike()
        {
            $data = [
                'article_id' => $_POST["article_id"]
            ];

            $errors = [
                'article_id_err' => '' 
            ];

            if (empty($data['article_id']) || ! Article::find($data['article_id'])) {
                $errors['article_id_err'] = "The article not found.";
            }

            if (Dislike::find($data['article_id'], user()->getId())) {
                Dislike::remove($data["article_id"], user()->getId());
                redirect('articles/'. $data["article_id"]);
                return;
            }

            if (empty(array_filter($errors))) {

                Like::remove($data["article_id"], user()->getId());
                $dislike = new Dislike(null, $data["article_id"], user()->getId());
                $dislike->save();
            }
    
            flash("error", array_first_not_null_value($errors));
            redirect('articles/'. $data["article_id"]);
        }

        public function addToFavorite()
        {
            $data = [
                'article_id' => $_POST["article_id"]
            ];

            $errors = [
                'article_id_err' => '' 
            ];

            if (empty($data['article_id']) || ! Article::find($data['article_id'])) {
                $errors['article_id_err'] = "The article not found.";
            }

            if (Favorite::find($data['article_id'], user()->getId())) {
                Favorite::remove($data["article_id"], user()->getId());
                redirect('articles/favorites');
                return;
            }

            if (empty(array_filter($errors))) {

                $favorite = new Favorite(null, $data["article_id"], user()->getId());
                if ($favorite->save()) {
                    flash("success", "Article added to your favorites.");
                    redirect('articles/favorites');
                }
            }
    
            flash("error", array_first_not_null_value($errors));
            redirect('articles/favorites');
        }
    }