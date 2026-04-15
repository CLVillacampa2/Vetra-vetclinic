<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Patient;
use App\Models\Owner;
use App\Models\InventoryItem;
use App\Models\MedicalRecord;

class ClinicalController extends Controller
{
    public function register(Request $request) {
        try {
            if (User::where('username', $request->username)->exists()) {
                return response()->json(['success' => false, 'error' => 'Username is already taken. Please pick another.']);
            }

            $user = User::create([
                'name' => $request->name ? $request->name : 'Clinical Operator',
                'username' => $request->username,
                'email' => $request->username . rand(1000, 9999) . '@vetra.titan',
                'password' => Hash::make($request->password)
            ]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // 🌟 NEW: Profile Update Logic
 public function updateProfile(Request $request) {
        try {
            $user = Auth::user();

            // SAFETY CHECK: Make sure Laravel actually knows who is logged in!
            if (!$user) {
                return response()->json(['success' => false, 'error' => 'User not authenticated.']);
            }

            // Check if the new username is already taken by someone else
            if (User::where('username', $request->username)->where('id', '!=', $user->id)->exists()) {
                return response()->json(['success' => false, 'error' => 'Username is already taken.']);
            }
            
            // Check if the new email is already taken by someone else
            if (User::where('email', $request->email)->where('id', '!=', $user->id)->exists()) {
                return response()->json(['success' => false, 'error' => 'Email is already taken.']);
            }

            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;

            // Only update the password if the user typed a new one
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json(['success' => true, 'user' => $user]);
        } catch (\Exception $e) {
            // This will now tell you EXACTLY what line of code crashed if it fails again
            return response()->json(['success' => false, 'error' => 'System Error: ' . $e->getMessage()]);
        }
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

    public function getAllData() {
        return response()->json([
            'user' => Auth::user(),
            'patients' => Patient::with('records')->orderBy('updated_at', 'desc')->get(),
            'owners' => Owner::all(),
            'inventory' => InventoryItem::all(),
            'recent' => Patient::whereHas('records')
                        ->orWhereColumn('updated_at', '>', 'created_at')
                        ->orderBy('updated_at', 'desc')
                        ->take(5)
                        ->get()
        ]);
    }

    public function saveRecord(Request $request) {
        try {
            $type = $request->type;
            $payload = $request->payload;

            if (empty($payload['id'])) {
                unset($payload['id']);
                $match = ['id' => null];
            } else {
                $match = ['id' => $payload['id']];
            }

            if ($type === 'Patient') {
                $ageString = strtolower($payload['age'] ?? '');
                $payload['is_young'] = (str_contains($ageString, 'month') || (intval($ageString) <= 1)) ? true : false;
                Patient::updateOrCreate($match, $payload);
            } elseif ($type === 'Owner') {
                Owner::updateOrCreate($match, $payload);
            } elseif ($type === 'Inventory' || $type === 'InventoryItem') {
                $payload['critical'] = (int)($payload['stock'] ?? 0) <= (int)($payload['threshold'] ?? 0);
                InventoryItem::updateOrCreate($match, $payload);
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function deleteData(Request $request) {
        try {
            $type = $request->type;
            $id = $request->id;

            if ($type === 'Patient') Patient::destroy($id);
            elseif ($type === 'Owner') Owner::destroy($id);
            elseif ($type === 'Inventory' || $type === 'InventoryItem') InventoryItem::destroy($id);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function saveMedicalRecord(Request $request) {
        try {
            if (!$request->patient_id) {
                return response()->json(['success' => false, 'error' => 'Missing Patient Database ID'], 400);
            }

            // 🌟 NEW: Inventory Deduction Logic
            if ($request->has('inventory_id') && $request->quantity > 0) {
                $item = InventoryItem::find($request->inventory_id);
                
                if ($item) {
                    // Check if we have enough stock before saving
                    if ($item->stock < $request->quantity) {
                        return response()->json(['success' => false, 'error' => 'Not enough stock! Only ' . $item->stock . ' left.']);
                    }
                    
                    // Deduct the stock and update critical status
                    $item->stock -= $request->quantity;
                    $item->critical = (int)$item->stock <= (int)$item->threshold;
                    $item->save();
                }
            }

            // Save the medical record
            MedicalRecord::updateOrCreate(
                ['id' => $request->id ?? null], 
                [
                    'patient_id' => $request->patient_id,
                    'problem'    => $request->problem,
                    'action'     => $request->action,
                    'medicine'   => $request->medicine ?? 'None',
                    'date'       => $request->date
                ]
            );
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function deleteMedicalRecord(Request $request) {
        try {
            MedicalRecord::destroy($request->id);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
};