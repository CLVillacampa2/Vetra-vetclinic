<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Create the Admin User so teachers can log in!
        User::create([
            'name' => 'VETRA Admin',
            'username' => 'admin',
            'email' => 'admin@vetra.com',
            'password' => Hash::make('password123'), 
        ]);

// 2. Create a dummy Inventory Item
        DB::table('inventory_items')->insert([
            'name' => 'Rabies Vaccine',
            'category' => 'Vaccine', 
            'stock' => 50,
            'unit' => 'Vials', 
            'threshold' => '10', 
            'supplier' => 'VET Pharma Inc.', 
            'restocked' => '2026-04-10', 
            'critical' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
// 3. Create a dummy Owner
        $ownerId = DB::table('owners')->insertGetId([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com', 
            'phone' => '09123456789',   
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        
// 4. Create a dummy Pet (Patient)
        DB::table('patients')->insert([
            'patient_id' => 'PT-001',  
            'name' => 'Buddy',
            'age' => '3 Years',  
            'birthday' => '2023-01-15',   
            'breed' => 'Golden Retriever',
            'type' => 'Dog',         
            'is_young' => false,           
            'owner' => 'John Doe',   
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
