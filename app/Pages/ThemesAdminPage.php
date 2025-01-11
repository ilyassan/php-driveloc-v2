<?php

    class ThemesAdminPage extends BasePage
    {
        public function index()
        {

            $themes = Theme::all();

            $this->render('/themes/index', compact('themes'));
        }

        public function store()
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
            $data = [
                'theme_name' => trim($_POST['theme_name']),
                'theme_description' => trim($_POST['theme_description']),
                'image' => $_FILES['image'],
            ];

            $errors = [
                'theme_name_err' => '',
                'theme_description_err' => '',
                'image_err' => '',
                'general_err' => '',
            ];
    
            // Validate the Inputs Data
            if (empty($data['theme_name'])) {
                $errors['theme_name_err'] = 'Please enter the theme name.';
            }
    
            if (empty($data['theme_description'])) {
                $errors['theme_description_err'] = 'Please enter the theme description.';
            }
    
            $imageName = '';
            if (empty($data['image']['name']) || $data['image']['size'] == 0) {
                $errors['image_err'] = 'Vehicle image is required.';
            }
            elseif ($data['image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($data['image']['type'], $allowedTypes)) {
                    $uploadDir = IMAGESROOT . 'themes/';
                    
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $imageName = time() . '_' . basename($data['image']['name']); // Generate unique filename
                    $imagePath = $uploadDir . $imageName;

                    if (!move_uploaded_file($data['image']['tmp_name'], $imagePath)) {
                        $errors['image_err'] = 'Failed to upload the image.';
                    }
                } else {
                    $errors['image_err'] = 'Invalid image format. Allowed formats are JPG, PNG, and GIF.';
                }
            } elseif ($data['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $errors['image_err'] = 'Error uploading the image.';
            }
    
            // Check for errors and store into Database
            if (empty(array_filter($errors))) {

                $theme = new Theme(null, $data['theme_name'], $data['theme_description'], $imageName);

                if ($theme->save()) {
                    flash("success", "Theme added successfully!");
                    redirect('themes');
                } else {
                    $errors['general_err'] = 'Something went wrong while creating the theme.';
                }
            }
    
            flash("error", array_first_not_null_value($errors));
            redirect('themes');
        }

        public function delete()
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id = $_POST['theme_id'];
            
            $theme = Theme::find($id);
            $theme->delete();

            flash("success", "Theme '" . $theme->getName() . "' deleted successfully.");
            redirect("themes");
        }
    }