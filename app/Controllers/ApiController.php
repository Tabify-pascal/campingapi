<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ApiKeyModel;
use App\Models\ReservationModel;
use App\Models\SpotAvailabilityModel;
use CodeIgniter\HTTP\ResponseInterface;

class ApiController extends BaseController
{
    public function index()
    {
        return $this->response->setJson(['message' => 'Hello World'], 200);
    }

    public function insert()
    {
        $reservationModel = new ReservationModel();
        $availabilityModel = new SpotAvailabilityModel();

        // Retrieve the POST data
        $post = json_decode($this->request->getBody());

        // Validate that the necessary keys exist in the POST data
        if (!isset($post->spot, $post->date_from, $post->date_to)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Spot, start date, and end date are required.',
            ]);
        }

        $spot_id = $post->spot;
        $date_from = $post->date_from;
        $date_to = $post->date_to;

        // Check if the spot is available for the entire date range
        $currentDate = strtotime($date_from);
        $endDate = strtotime($date_to);

        while ($currentDate <= $endDate) {
            $date = date('Y-m-d', $currentDate);

            if (!$availabilityModel->isSpotAvailable($spot_id, $date)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => "Spot is not available from $date_from to $date_to.",
                ]);
            }

            $currentDate = strtotime('+1 day', $currentDate);
        }

        // Reserve the spot for the entire date range
        $currentDate = strtotime($date_from);
        while ($currentDate <= $endDate) {
            $date = date('Y-m-d', $currentDate);

            if (!$availabilityModel->reserveSpot($spot_id, $date)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => "Failed to reserve the spot for $date. Please try again.",
                ]);
            }

            $currentDate = strtotime('+1 day', $currentDate);
        }

        // Prepare the reservation data
        $data = [
            'name'             => $post->name,
            'phone_number'     => $post->phone_number,
            'email'            => $post->email,
            'date_reservation' => $date_from,  // Use the start date for the reservation record
            'guests'           => $post->guests,
            'spot'             => $spot_id,
            'comment'          => $post->comment,
        ];

        // Try to save the data to the database
        if ($reservationModel->insert($data)) {
            return $this->response->setStatusCode(201)->setJSON([
                'success' => true,
                'message' => "Reservation successfully created from $date_from to $date_to.",
            ]);
        } else {
            // If there's an error, return the validation messages
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'errors'  => $reservationModel->errors(),
            ]);
        }
    }

    public function getAvailableDates()
    {
        $availabilityModel = new SpotAvailabilityModel();

        // Retrieve the POST data
        $post = json_decode($this->request->getBody());

        // Validate that the necessary keys exist in the POST data
        if (!isset($post->spot_id, $post->date_from, $post->date_to)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Spot ID, start date, and end date are required.',
            ]);
        }

        $spot_id = $post->spot_id;
        $date_from = $post->date_from;
        $date_to = $post->date_to;

        // Initialize an array to hold the available dates
        $availableDates = [];

        // Loop through each date in the range and check availability
        $currentDate = strtotime($date_from);
        $endDate = strtotime($date_to);

        while ($currentDate <= $endDate) {
            $date = date('Y-m-d', $currentDate);

            if ($availabilityModel->isSpotAvailable($spot_id, $date)) {
                $availableDates[] = $date;
            }

            $currentDate = strtotime('+1 day', $currentDate);
        }

        // Return the available dates
        return $this->response->setJSON([
            'success' => true,
            'available_dates' => $availableDates,
        ]);
    }


    public function get_reservations_between_dates()
    {
        $reservationModel = new ReservationModel();
        $post = json_decode($this->request->getBody());

        $data = $reservationModel
            ->select('id, name, phone_number, email, date_reservation, guests, spot, comment, created_at, updated_at') 
            ->where('date_reservation >=', $post->date_start)
            ->where('date_reservation <=', $post->date_end)
            ->where('user_id', $this->get_user_id())
            ->findAll();
    

        if($data == null) {
            $data = "Er zijn geen reserveringen tussen deze datums";
        }
        
        return $this->response->setStatusCode(201)->setJSON([
            'success' => true,
            'data'=> $data,
        ]);
    }

    
    public function get_all()
    {
        $reservationModel = new ReservationModel();
        $data = $reservationModel
            ->select('id, name, phone_number, email, date_reservation, guests, spot, comment, created_at, updated_at') // Specificeer alle velden behalve user_id
            ->where('user_id', $this->get_user_id())
            ->findAll();
    

        if($data == null) {
            $data = "Je hebt geen reserveringen";
        }
        
        return $this->response->setStatusCode(201)->setJSON([
            'success' => true,
            'data'=> $data,
        ]);
    }



    private function get_user_id(){
        $apiKeyModel = new ApiKeyModel();
        $authHeader = $this->request->getHeaderLine('Authorization');
        $apiKey = substr($authHeader, 7);
        $apiKeyRecord = $apiKeyModel->where('api_key', $apiKey)->first();

        return $apiKeyRecord['user_id'];
    }
    

    public function store()
    {
        $apiKeyModel = new ApiKeyModel();
        $userId = auth()->id();
    
        // Controleer of er al een API-sleutel is voor deze gebruiker
        $existingApiKey = $apiKeyModel->where('user_id', $userId)->first();
    
        // Genereer een nieuwe API-sleutel
        $apiKey = base64_encode(random_bytes(32));
    
        if ($existingApiKey) {
            // Als er al een API-sleutel is, werk deze dan bij
            $existingApiKey['api_key'] = $apiKey;
    
            if ($apiKeyModel->update($existingApiKey['id'], $existingApiKey)) {
                return $this->response->setJSON(['success' => true, 'api_key' => $apiKey]);
            } else {
                return $this->response->setJSON(['success' => false, 'error' => 'Could not update API key.']);
            }
        } else {
            // Als er geen API-sleutel is, maak een nieuwe
            if ($apiKeyModel->save(['api_key' => $apiKey, 'user_id' => $userId])) {
                return $this->response->setJSON(['success' => true, 'api_key' => $apiKey]);
            } else {
                return $this->response->setJSON(['success' => false, 'error' => 'Could not save API key.']);
            }
        }
    }

    public function populateDatabase()
    {
        $reservationModel = new ReservationModel();
        $availabilityModel = new SpotAvailabilityModel();
        $campsiteSpotModel = new \App\Models\CampsiteSpotModel();

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

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Database successfully populated with random reservations and availability data.',
        ]);
    }
    
}
