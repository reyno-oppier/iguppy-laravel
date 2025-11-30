<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class DashboardController extends Controller
{
    protected FirebaseService $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        $db = $this->firebase->getDatabase();
        $sensors = $db->getReference('sensors')->getValue() ?? [];
    
        // --- RUN PYTHON PREDICTION SCRIPT ---
        $python = base_path('venv/Scripts/python.exe'); // Adjust path if needed
        $script = base_path('ml/predict.py');
    
        $output = [];
        $returnVar = 0;
    
        exec("\"$python\" \"$script\" 1", $output, $returnVar); // Run Python
    
        $predictions = [];
        if ($returnVar === 0 && !empty($output)) {
            $json = end($output); // last line from Python output
            $predictions = json_decode($json, true);
        }
    
        return view('dashboard', [
            'sensors' => $sensors,
            'predictions' => $predictions
        ]);
    }
    
}
