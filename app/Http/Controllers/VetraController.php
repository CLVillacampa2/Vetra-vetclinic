<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Patient;
use App\Models\Owner;
use App\Models\InventoryItem;
use App\Models\MedicalRecord;

class VetraController extends Controller
{
    public function register(Request $request) {
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->username . '@vetra.titan',
            'password' => Hash::make($request->password)
        ]);
        return response()->json(['success' => true]);
    }

    public function login(Request $request) {
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return response()->json(['success' => true, 'user' => Auth::user()]);
        }
        return response()->json(['success' => false], 401);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['success' => true]);
    }

    public function getData() {
        return response()->json([
            'patients' => Patient::with('records')->get(),
            'owners' => Owner::all(),
            'inventory' => InventoryItem::all(),
            // RECENT ACTIVITY: Only patients updated in the last 24 hours
            // OR patients that have at least one medical record
            'recent' => Patient::where('updated_at', '>=', now()->subDay())
                        ->orHas('records')
                        ->orderBy('updated_at', 'desc')
                        ->take(5)
                        ->get(),
            'user' => Auth::user()
        ]);
    }

    public function saveData(Request $request) {
        $type = $request->type;
        $data = $request->payload;

        if ($type === 'Patient') {
            // This updates the 'updated_at' timestamp so they appear in Recent Activity
            Patient::updateOrCreate(['id' => $data['id'] ?? null], $data);
        } elseif ($type === 'Owner') {
            Owner::updateOrCreate(['id' => $data['id'] ?? null], $data);
        } elseif ($type === 'Inventory') {
            // Set critical status automatically
            $data['critical'] = $data['stock'] <= $data['threshold'];
            InventoryItem::updateOrCreate(['id' => $data['id'] ?? null], $data);
        }
        return response()->json(['success' => true]);
    }

    public function deleteData(Request $request) {
        if ($request->type === 'Patient') Patient::destroy($request->id);
        elseif ($request->type === 'Owner') Owner::destroy($request->id);
        elseif ($request->type === 'Inventory') InventoryItem::destroy($request->id);
        return response()->json(['success' => true]);
    }

    public function saveMedicalRecord(Request $request) {
        MedicalRecord::updateOrCreate(['id' => $request->id ?? null], $request->all());
        return response()->json(['success' => true]);
    }

    public function deleteMedicalRecord(Request $request) {
        MedicalRecord::destroy($request->id);
        return response()->json(['success' => true]);
    }

}