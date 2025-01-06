<?php

    class VehiclesDetailsPage extends BasePage
    {
        public function index($id)
        {
            $vehicle = Vehicle::find($id);
            $notAvailableDates = $vehicle->getNotAvailableDates();
            $places = Place::all();
            
            $this->render("/vehicles/show", compact("vehicle", "notAvailableDates", "places"));
        }

        public function store()
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'vehicle_id' => trim($_POST['vehicle_id']),
                'client_id' => user()->getId(),
                'from_date' => trim($_POST['from_date']),
                'to_date' => trim($_POST['to_date']),
                'place_id' => trim($_POST['place_id']),
            ];

            $errors = [
                'vehicle_id_err' => '',
                'from_date_err' => '',
                'to_date_err' => '',
                'place_id_err' => '',
                'general_err' => '',
            ];

            if (empty($data['vehicle_id']) || !Vehicle::find($data['vehicle_id'])) {
                $errors['vehicle_id_err'] = 'Unavailable car.';
            }

            // Validate Date
            if (empty($data['from_date'])) {
                $errors['from_date_err'] = 'Please select a pickup date.';
            }

            if (empty($data['to_date'])) {
                $errors['to_date_err'] = 'Please select a return date.';
            } elseif ($data['to_date'] < $data['from_date']) {
                $errors['to_date_err'] = 'Return date must be after pickup date.';
            }

            // Validate Place ID
            if (empty($data['place_id'])) {
                $errors['place_id_err'] = 'Please select a location.';
            }

            // Check for date conflicts
            if (empty($errors['from_date_err']) && empty($errors['to_date_err'])) {
                if (Reservation::isVehicleReserved($data['vehicle_id'], $data['from_date'], $data['to_date'])) {
                    $errors['general_err'] = 'This vehicle is already reserved for the selected dates.';
                }
            }
            
            // If no errors, create the reservation
            if (empty($errors['vehicle_id_err']) && empty($errors['from_date_err']) && empty($errors['to_date_err']) && empty($errors['place_id_err']) && empty($errors['general_err'])) {

                // Create the reservation
                $reservation = new Reservation(null, $data['from_date'], $data['to_date'], $data['place_id'], $data['vehicle_id'], $data['client_id']);

                if ($reservation->save()) {
                    flash("success", "Reservation created successfully!");
                    redirect('reservations');
                } else {
                    $errors['general_err'] = 'Something went wrong while creating the reservation.';
                }
            }

            if (!empty(array_filter($errors))) {
                // Load view with the errors
                flash("error", array_first_not_null_value($errors));
                redirect('vehicles/'. $data['vehicle_id']);
            }
        }
    }