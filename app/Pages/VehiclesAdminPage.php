<?php

    class VehiclesAdminPage extends BasePage
    {
        public function index()
        {
            $vehicles = Vehicle::all();
            $categories = Category::all();

            $this->render("/vehicles/index", compact("vehicles", "categories"));
        }

        public function getFilteredVehicles(){
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            $filters['name'] = $data['name'] ?? '';
            $filters['category_id'] = $data['category_id'] ?? '';
            $filters['min_price'] = $data['min_price'] ?? '';
            $filters['max_price'] = $data['max_price'] ?? '';

            $vehicles = Vehicle::all($filters);
            echo json_encode(["success" => true, "data" => $vehicles]);
        }

        public function create()
        {
            $categories = Category::all();
            $types = Type::all();

            $this->render("/vehicles/create", compact("categories", "types"));
        }

        public function store()
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
            $data = [
                'vehicle_name' => trim($_POST['vehicle_name']),
                'vehicle_model' => trim($_POST['vehicle_model']),
                'category_id' => trim($_POST['category_id']),
                'type_id' => trim($_POST['type_id']),
                'seats' => trim($_POST['seats']),
                'price_per_day' => trim($_POST['price_per_day']),
                'image' => $_FILES['image'],
            ];
    
            $errors = [
                'vehicle_name_err' => '',
                'vehicle_model_err' => '',
                'category_id_err' => '',
                'type_id_err' => '',
                'seats_err' => '',
                'price_per_day_err' => '',
                'image_err' => '',
                'general_err' => '',
            ];
    
            // Validate the Inputs Data
            if (empty($data['vehicle_name'])) {
                $errors['vehicle_name_err'] = 'Please enter the vehicle name.';
            }
    
            if (empty($data['vehicle_model'])) {
                $errors['vehicle_model_err'] = 'Please enter the vehicle model.';
            }
    
            if (empty($data['category_id'])) {
                $errors['category_id_err'] = 'Please select a vehicle category.';
            }
    
            if (empty($data['type_id'])) {
                $errors['type_id_err'] = 'Please select a vehicle type.';
            }
    
            if (empty($data['seats'])) {
                $errors['seats_err'] = 'Please enter the number of seats.';
            } elseif (!is_numeric($data['seats']) || $data['seats'] <= 0) {
                $errors['seats_err'] = 'Number of seats must be a positive number.';
            }
    
            if (empty($data['price_per_day'])) {
                $errors['price_per_day_err'] = 'Please enter the price per day.';
            } elseif (!is_numeric($data['price_per_day']) || $data['price_per_day'] <= 0) {
                    $errors['price_per_day_err'] = 'Price per day must be a positive number.';
            }

            $imageName = '';
            
            if (empty($data['image']['name']) || $data['image']['size'] == 0) {
                $errors['image_err'] = 'Vehicle image is required.';
            }
            elseif ($data['image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($data['image']['type'], $allowedTypes)) {
                    $uploadDir = IMAGESROOT . 'vehicles/';
                    
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

                $vehicle = new Vehicle(null, $data['vehicle_name'], $data['vehicle_model'], $data['seats'], $data['price_per_day'], $imageName, $data['type_id'], $data['category_id']);

                if ($vehicle->save()) {
                    flash("success", "Vehicle added successfully!");
                    redirect('vehicles');
                } else {
                    $errors['general_err'] = 'Something went wrong while adding the vehicle.';
                }
            }
    
            flash("error", array_first_not_null_value($errors));
            redirect('vehicles/create');
        }

        public function edit($id)
        {
            $vehicle = Vehicle::find($id);
            $categories = Category::all();
            $types = Type::all();

            $this->render("/vehicles/edit", compact("vehicle", "categories", "types"));
        }

        public function update($id)
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
            $data = [
                'vehicle_name' => trim($_POST['vehicle_name']),
                'vehicle_model' => trim($_POST['vehicle_model']),
                'category_id' => trim($_POST['category_id']),
                'type_id' => trim($_POST['type_id']),
                'seats' => trim($_POST['seats']),
                'price_per_day' => trim($_POST['price_per_day']),
                'image' => $_FILES['image']
            ];
    
            $errors = [
                'vehicle_name_err' => '',
                'vehicle_model_err' => '',
                'category_id_err' => '',
                'type_id_err' => '',
                'seats_err' => '',
                'price_per_day_err' => '',
                'image_err' => '',
                'general_err' => ''
            ];
    
            // Validate the Inputs Data
            if (empty($data['vehicle_name'])) {
                $errors['vehicle_name_err'] = 'Please enter the vehicle name.';
            }
    
            if (empty($data['vehicle_model'])) {
                $errors['vehicle_model_err'] = 'Please enter the vehicle model.';
            }
    
            if (empty($data['category_id'])) {
                $errors['category_id_err'] = 'Please select a vehicle category.';
            }
    
            if (empty($data['type_id'])) {
                $errors['type_id_err'] = 'Please select a vehicle type.';
            }
    
            if (empty($data['seats'])) {
                $errors['seats_err'] = 'Please enter the number of seats.';
            } elseif (!is_numeric($data['seats']) || $data['seats'] <= 0) {
                $errors['seats_err'] = 'Number of seats must be a positive number.';
            }
    
            if (empty($data['price_per_day'])) {
                $errors['price_per_day_err'] = 'Please enter the price per day.';
            } elseif (!is_numeric($data['price_per_day']) || $data['price_per_day'] <= 0) {
                    $errors['price_per_day_err'] = 'Price per day must be a positive number.';
            }

            $imageName = '';
            if ($data['image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($data['image']['type'], $allowedTypes)) {
                    $uploadDir = IMAGESROOT . 'vehicles/';
                    $imageName = time() . '_' . basename($data['image']['name']);
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
                $vehicle = Vehicle::find($id);
            
                $vehicle->setName($data['vehicle_name']);
                $vehicle->setModel($data['vehicle_model']);
                $vehicle->setCategoryId($data['category_id']);
                $vehicle->setTypeId($data['type_id']);
                $vehicle->setSeats($data['seats']);
                $vehicle->setPrice($data['price_per_day']);
            
                if (!empty($imageName)) {            
                    $existingImage = $vehicle->getImageName();
                    if (!empty($existingImage)) {
                        $oldImagePath = $uploadDir . $existingImage;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    $vehicle->setImageName($imageName);
                }
            
                if ($vehicle->update()) {
                    flash("success", "Vehicle Updated successfully!");
                    redirect('vehicles/edit/' . $id);
                } else {
                    $errors['general_err'] = 'Something went wrong while updating the vehicle.';
                }
            }
    
            flash("error", array_first_not_null_value($errors));
            redirect('vehicles/edit/'. $id);
        }

        public function delete()
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $id = $_POST['vehicle_id'];
            
            $vehicle = Vehicle::find($id);
            $vehicle->delete();

            flash("success", "Vehicle '" . $vehicle->getName() . "' deleted successfully.");
            redirect("vehicles");
        }
    }