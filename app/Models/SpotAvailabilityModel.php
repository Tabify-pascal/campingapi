<?php

namespace App\Models;

use CodeIgniter\Model;

class SpotAvailabilityModel extends Model
{
    protected $table = 'spot_availabilities';
    protected $primaryKey = 'id';
    protected $allowedFields = ['spot_id', 'date', 'is_available', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    public function isSpotAvailable($spot_id, $date)
    {
        // Check if there's an entry marking the spot as unavailable
        $availability = $this->where('spot_id', $spot_id)
                            ->where('date', $date)
                            ->where('is_available', false)
                            ->first();

        // If there's no entry or the spot is available, return true
        return $availability === null;
    }

    public function reserveSpot($spot_id, $date)
    {
        // Check if there's already an entry marking the spot as unavailable
        $availability = $this->where('spot_id', $spot_id)
                            ->where('date', $date)
                            ->first();

        if ($availability) {
            // If the spot is already marked as unavailable, return false
            if (!$availability['is_available']) {
                return false;
            }

            // Update the existing entry to mark it as unavailable
            $availability['is_available'] = false;
            return $this->update($availability['id'], $availability);
        }

        // If there's no entry, create a new one to mark it as unavailable
        return $this->insert([
            'spot_id' => $spot_id,
            'date' => $date,
            'is_available' => false,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function fillSpotAvailability($spot_id, $startDate, $endDate)
    {
        $data = [];
        $currentDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        while ($currentDate <= $endDate) {
            $date = date('Y-m-d', $currentDate);
            $data[] = [
                'spot_id' => $spot_id,
                'date' => $date,
                'is_available' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $currentDate = strtotime('+1 day', $currentDate);
        }

        return $this->insertBatch($data);
    }
}
