<?php

    class VehiclesPage extends BasePage
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
    }