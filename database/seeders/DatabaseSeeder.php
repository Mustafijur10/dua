<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{User,Category};
class DatabaseSeeder extends Seeder { public function run():void {
 User::firstOrCreate(['email'=>'admin@noor.local'],['name'=>'Noor Admin','password'=>Hash::make('NoorDaily!2026')]);
 foreach([['Morning & evening','☀'],['Gratitude','♡'],['Protection','✦'],['Patience','❋'],['Travel','⌁']] as [$name,$icon])Category::firstOrCreate(['name'=>$name],['icon'=>$icon,'color'=>'sage']);
} }
