<?php

namespace App\Controllers;

use App\Models\SpotAvailabilityModel;

class CampsiteController extends BaseController
{
    public function checkAvailability($spot_id, $date)
    {
        $availabilityModel = new SpotAvailabilityModel();
        $isAvailable = $availabilityModel->isSpotAvailable($spot_id, $date);

        if ($isAvailable) {
            return $this->response->setJSON(['status' => 'available']);
        } else {
            return $this->response->setJSON(['status' => 'unavailable']);
        }
    }

    public function reserveSpot($spot_id, $date)
    {
        $availabilityModel = new SpotAvailabilityModel();
        $reserved = $availabilityModel->reserveSpot($spot_id, $date);

        if ($reserved) {
            return $this->response->setJSON(['status' => 'reserved']);
        } else {
            return $this->response->setJSON(['status' => 'unavailable or already reserved']);
        }
    }

    public function fillAvailability($spot_id, $startDate, $endDate)
    {
        $availabilityModel = new SpotAvailabilityModel();
        $filled = $availabilityModel->fillSpotAvailability($spot_id, $startDate, $endDate);

        if ($filled) {
            return $this->response->setJSON(['status' => 'availability filled']);
        } else {
            return $this->response->setJSON(['status' => 'error']);
        }
    }
}