<?php

namespace App\Http\Controllers;
use App\Models\Patient;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function store(Request $request) {
        // Логіка додавання пацієнта
    }
    public function destroy($id) {
        // Логіка видалення пацієнта
    }
    
    public function search(Request $request)
    {
        //echo '111111111111111';die;
        $query = $request->get('query');
        \Log::info('Received search query: ' . $query);
        $patients = Patient::search($query);
        \Log::debug('Search results:', $patients->toArray());
        return response()->json($patients);
    }
}
