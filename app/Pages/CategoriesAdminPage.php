<?php

    class CategoriesAdminPage extends BasePage
    {
        public function index()
        {
            $categories = Category::all();

            $this->render("/categories/index", compact("categories"));
        }


        public function store()
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'categories_name' => array_map('trim', $_POST['categories_name']),
            ];
    
            $errors = [
                'categories_name_err' => '',
                'general_err' => '',
            ];
    
            // Validate the Inputs Data
            if (count(array_filter($data['categories_name'])) == 0) {
                $errors['categories_name_err'] = 'Please enter the category name.';
            }

            // Check if there are no errors
            if (empty($errors['categories_name_err'])) {
                foreach ($data['categories_name'] as $category_name) {
                    if (!empty($category_name)) {
                        $category = new Category(null, $category_name);
                        $category->save();
                    }
                }

                flash("success", "Categories created successfully.");
                redirect("categories");
            }

            flash("error", array_first_not_null_value($errors));
            redirect("categories");
        }

        public function delete()
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id = $_POST['category_id'];
            
            $category = Category::find($id);
            $category->delete();

            flash("success", "Category removed successfully.");
            redirect("categories");
        }

    }