<?php
    class DashboardPage extends BasePage
    {
        public function index()
        {
            [$monthProfit, $ratioProfit] = $this->monthProfit();
            [$monthReservations, $diffReservations] = $this->monthReservations();
            $usersCount = User::count();
            $averageRating = Rate::avg();
            $popularCategories = Category::popularCategories();
            $lastSixMonthsRevenue = $this->getLastSixMonthsRevenue();
            $recentActivities = $this->getRecentActivities(3);
            $topVehicle = Vehicle::topVehicle();

            $this->render("/",
                compact(
                'monthProfit', 'ratioProfit',
                'monthReservations', 'diffReservations',
                'usersCount', 'popularCategories',
                'lastSixMonthsRevenue', 'recentActivities',
                'averageRating', 'topVehicle'
            ));
        }

        private function monthProfit(){
            $startMonth = date('Y-m-01');
            $endMonth = date('Y-m-t');

            $lastMonthStart = date('Y-m-01', strtotime('-1 month'));
            $lastMonthEnd = date('Y-m-t', strtotime('-1 month'));

            $monthProfit = Reservation::getReservationsCostBetween($startMonth, $endMonth);
            $lastMonthProfit = Reservation::getReservationsCostBetween($lastMonthStart, $lastMonthEnd);

            $ratio = 100;
            if($lastMonthProfit > 0){
                $ratio = ($monthProfit - $lastMonthProfit) / $lastMonthProfit * 100;
            }

            return [$monthProfit, $ratio];
        }

        private function monthReservations(){
            $startMonth = date('Y-m-01');
            $endMonth = date('Y-m-t');

            $lastMonthStart = date('Y-m-01', strtotime('-1 month'));
            $lastMonthEnd = date('Y-m-t', strtotime('-1 month'));

            $monthReservations = Reservation::getReservationsCount($startMonth, $endMonth);
            $lastMonthReservations = Reservation::getReservationsCount($lastMonthStart, $lastMonthEnd);

            
            $diff = $monthReservations - $lastMonthReservations;

            return [$monthReservations, $diff];
        }

        private function getLastSixMonthsRevenue()
        {
            $revenues = [];
            for ($i = 5; $i >= 0; $i--) {
                $startDate = date('Y-m-01', strtotime("-$i months"));
                $endDate = date('Y-m-t', strtotime("-$i months"));
                $monthLabel = date('M', strtotime("-$i months"));

                $revenue = Reservation::getReservationsCostBetween($startDate, $endDate);
                
                $revenues[$monthLabel] = $revenue;
            }

            $startDate = date('Y-m-01', strtotime("-1 months"));
            $endDate = date('Y-m-t', strtotime("-1 months"));

            $revenue = Reservation::getReservationsCostBetween($startDate, $endDate);

            return $revenues;
        }

        private function getRecentActivities($limit = 5)
        {
            $recentReservations = Reservation::getRecentReservations($limit);
            $recentRegistrations = User::getRecentRegistrations($limit);
            $recentRatings = Rate::getRecentRates($limit);
            
            $activities = [];
            foreach ($recentReservations as $reservation) {
                $activities[] = [
                    'type' => 'reservation',
                    'message' => 'New reservation: ' . $reservation["vehicle_name"],
                    'created_at' => $reservation["created_at"],
                ];
            }

            foreach ($recentRegistrations as $client) {
                $activities[] = [
                    'type' => 'registration',
                    'message' => 'New client registration',
                    'created_at' => $client["created_at"],
                ];
            }

            foreach ($recentRatings as $rate) {
                $activities[] = [
                    'type' => 'rate',
                    'message' => 'New 5-star rate: ' . $rate["vehicle_name"],
                    'created_at' => $rate["created_at"],
                ];
            }

            // sort by date
            usort($activities, function($a, $b){
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            // take 5 most recent
            $activities = array_slice($activities, 0, $limit);

            return $activities;
        }
    }