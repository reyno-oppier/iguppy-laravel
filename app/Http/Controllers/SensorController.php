<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Database;

class SensorController extends Controller
{
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function update(Request $request)
    {
        $ref = $this->database->getReference('sensors');

        $data = [
            'temperature' => $request->temperature,
            'ph' => $request->ph,
            'turbidity' => $request->turbidity,
            'waktu' => now()->toDateTimeString(),
        ];

        // Update main sensors node
        $ref->update($data);

        // Push a new entry into history
        $ref->getChild('history')->push($data);

        return response()->json(['success' => true]);
    }
}
