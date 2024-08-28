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

        // Haal de POST-gegevens op
        $post = json_decode($this->request->getBody());
        $spot_id = $post->spot;
        $date_reservation = $post->date_reservation;

         // Check if the spot is available on the requested date
        if (!$availabilityModel->isSpotAvailable($spot_id, $date_reservation)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Spot is not available on the selected date.',
            ]);
        }

        // Reserve the spot by setting its availability to false
        if (!$availabilityModel->reserveSpot($spot_id, $date_reservation)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Failed to reserve the spot. Please try again.',
            ]);
        }

        $data = [
            'name'             => $post->name,
            'phone_number'     => $post->phone_number,
            'email'            => $post->email,
            'date_reservation' => $date_reservation,
            'guests'           => $post->guests,
            'spot'             => $spot_id,
            'comment'          => $post->comment,
        ];

        log_message('info', print_r($data));
    
        // Probeer de gegevens op te slaan in de database
        if ($reservationModel->insert($data)) {
            return $this->response->setStatusCode(201)->setJSON([
                'success' => true,
                'message' => 'Reservation successfully created.',
            ]);
        } else {
            // Als er een fout is, retourneer dan de foutmeldingen
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'errors'  => $reservationModel->errors(),
            ]);
        }
    }


    public function get_reservations_between_dates()
    {
        $reservationModel = new ReservationModel();
        $post = json_decode($this->request->getBody());

        $data = $reservationModel
            ->select('id, name, phone_number, email, date_reservation, guests, spot, comment, created_at, updated_at') // Specificeer alle velden behalve user_id
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
    
}
