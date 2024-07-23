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
        $query = $request->get('query');
        $patients = Patient::search($query);
        return response()->json($patients);
    }
}
