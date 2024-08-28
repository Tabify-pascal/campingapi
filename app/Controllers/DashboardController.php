<?php

namespace App\Controllers;

use App\Models\ReservationModel;
use App\Models\SpotAvailabilityModel;
use App\Models\CampsiteSpotModel;
use CodeIgniter\Controller;

class DashboardController extends Controller
{
    public function index(): string
    {
        return view('dashboard');
    }

    public function submitReservation()
    {
        $reservationModel = new ReservationModel();
        $availabilityModel = new SpotAvailabilityModel();

        // Get POST data
        $post = $this->request->getPost();

        // Validate that the necessary keys exist in the POST data
        if (!isset($post['spot'], $post['date_from'], $post['date_to'])) {
            return redirect()->back()->with('error', 'Spot, start date, and end date are required.');
        }

        $spot_id = $post['spot'];
        $date_from = $post['date_from'];
        $date_to = $post['date_to'];

        // Check if the spot is available for the entire date range
        $currentDate = strtotime($date_from);
        $endDate = strtotime($date_to);

        while ($currentDate <= $endDate) {
            $date = date('Y-m-d', $currentDate);

            if (!$availabilityModel->isSpotAvailable($spot_id, $date)) {
                return redirect()->back()->with('error', 'Spot is not available from ' . $date_from . ' to ' . $date_to . '.');
            }

            $currentDate = strtotime('+1 day', $currentDate);
        }

        // Reserve the spot for the entire date range
        $currentDate = strtotime($date_from);
        while ($currentDate <= $endDate) {
            $date = date('Y-m-d', $currentDate);

            if (!$availabilityModel->reserveSpot($spot_id, $date)) {
                return redirect()->back()->with('error', 'Failed to reserve the spot for ' . $date . '. Please try again.');
            }

            $currentDate = strtotime('+1 day', $currentDate);
        }

        // Prepare the reservation data
        $data = [
            'name'             => $post['name'],
            'phone_number'     => $post['phone_number'],
            'email'            => $post['email'],
            'date_reservation' => $date_from,  // Use the start date for the reservation record
            'guests'           => $post['guests'],
            'spot'             => $spot_id,
            'comment'          => $post['comment'],
        ];

        // Try to save the data to the database
        if ($reservationModel->insert($data)) {
            return redirect()->back()->with('success', 'Reservation successfully created from ' . $date_from . ' to ' . $date_to . '.');
        } else {
            // If there's an error, return the validation messages
            return redirect()->back()->with('error', 'Failed to create reservation. Please check your input.');
        }
    }

    public function populateDatabase()
    {
        log_message('info','am here');
        $reservationModel = new ReservationModel();
        $availabilityModel = new SpotAvailabilityModel();
        $campsiteSpotModel = new CampsiteSpotModel();

        // Clear the existing data
        $reservationModel->truncate();
        $availabilityModel->truncate();

        // Get today's date
        $currentDate = strtotime(date('Y-m-d'));
        $endDate = strtotime('+100 days', $currentDate);

        // Get all spots
        $spots = $campsiteSpotModel->findAll();

        foreach ($spots as $spot) {
            $spot_id = $spot['id'];

            // Populate spot availability for the next 100 days
            $availabilityData = [];
            $currentDateTemp = $currentDate;
            while ($currentDateTemp <= $endDate) {
                $date = date('Y-m-d', $currentDateTemp);
                $availabilityData[] = [
                    'spot_id' => $spot_id,
                    'date' => $date,
                    'is_available' => true,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $currentDateTemp = strtotime('+1 day', $currentDateTemp);
            }
            $availabilityModel->insertBatch($availabilityData);

            // Make random reservations for this spot
            $reservationCount = rand(3, 10);
            for ($i = 0; $i < $reservationCount; $i++) {
                $reservationStartDate = date('Y-m-d', strtotime('+' . rand(0, 99) . ' days', $currentDate));
                $reservationEndDate = date('Y-m-d', strtotime('+' . rand(1, 7) . ' days', strtotime($reservationStartDate)));

                // Check if the spot is available for the entire range
                $currentDateTemp = strtotime($reservationStartDate);
                $available = true;
                while ($currentDateTemp <= strtotime($reservationEndDate)) {
                    $date = date('Y-m-d', $currentDateTemp);
                    if (!$availabilityModel->isSpotAvailable($spot_id, $date)) {
                        $available = false;
                        break;
                    }
                    $currentDateTemp = strtotime('+1 day', $currentDateTemp);
                }

                if ($available) {
                    // Reserve the spot
                    $currentDateTemp = strtotime($reservationStartDate);
                    while ($currentDateTemp <= strtotime($reservationEndDate)) {
                        $date = date('Y-m-d', $currentDateTemp);
                        $availabilityModel->reserveSpot($spot_id, $date);
                        $currentDateTemp = strtotime('+1 day', $currentDateTemp);
                    }

                    // Create the reservation record
                    $reservationModel->insert([
                        'name' => 'Random User ' . rand(1, 100),
                        'phone_number' => '123-456-789' . rand(0, 9),
                        'email' => 'randomuser' . rand(1, 100) . '@example.com',
                        'date_reservation' => $reservationStartDate,
                        'guests' => rand(1, 6),
                        'spot' => $spot_id,
                        'comment' => 'This is a random reservation.',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }

        return 'Database populated with random reservations and availability data.';
    }
}
