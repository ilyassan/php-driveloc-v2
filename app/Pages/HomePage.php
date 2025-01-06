<?php
    class HomePage extends BasePage
    {
        public function index()
        {
            $categoriesWithVehicles = Vehicle::getTopVehiclesByCategory();
            
            $this->render("/", compact("categoriesWithVehicles"));
        }
    }