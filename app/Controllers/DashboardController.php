<?php

namespace App\Controllers;

use App\Models\ReservationModel;
use App\Models\SpotAvailabilityModel;
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
}
