<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class FirebaseService
{
    protected Database $database;

    public function __construct()
    {
        $factory = (new Factory())
            ->withServiceAccount(base_path('storage/app/firebase/firebase-adminsdk.json'))
            ->withDatabaseUri('https://iguppydemo-default-rtdb.firebaseio.com');

        $this->database = $factory->createDatabase();
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * Update sensors data and automatically push to history
     */
    public function updateSensorData(array $newData)
    {
        $ref = $this->database->getReference('sensors');

        // Get current data
        $currentData = $ref->getValue() ?? [];

        // Push previous data to history if exists
        if (!empty($currentData)) {
            $historyRef = $ref->getChild('history');
            $historyRef->push([
                'temperature' => $currentData['temperature'] ?? null,
                'ph' => $currentData['ph'] ?? null,
                'turbidity' => $currentData['turbidity'] ?? null,
                'waktu' => $currentData['waktu'] ?? now()->toDateTimeString(),
            ]);
        }

        // Update main sensors data
        $ref->set(array_merge($newData, ['waktu' => now()->toDateTimeString()]));
    }
}
