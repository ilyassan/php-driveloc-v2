<?php

    class TagsPage extends BasePage
    {
        public function index()
        {
            $tags = Tag::all();

            $this->render('/tags/index', compact('tags'));
        }

        public function store()
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'tags_name' => array_map('trim', $_POST['tags_name']),
            ];
    
            $errors = [
                'tags_name_err' => '',
                'general_err' => '',
            ];
    
            // Validate the Inputs Data
            if (count(array_filter($data['tags_name'])) == 0) {
                $errors['tags_name_err'] = 'Please enter the category name.';
            }

            // Check if there are no errors
            if (empty($errors['tags_name_err'])) {
                foreach ($data['tags_name'] as $tag_name) {
                    if (!empty($tag_name)) {
                        $tag = new Tag(null, $tag_name);
                        $tag->save();
                    }
                }

                flash("success", "Tags created successfully.");
                redirect("tags");
            }

            flash("error", array_first_not_null_value($errors));
            redirect("tags");
        }

        public function delete()
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id = $_POST['tag_id'];
            
            $tag = Tag::find($id);
            $tag->delete();

            flash("success", "Tag '" . $tag->getName() . "' deleted successfully.");
            redirect("tags");
        }
    }