namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
public function run(): void
{
User::create([
'name' => 'Kepala Sekolah',
'email' => 'kepala@example.com',
'password' => Hash::make('password'),
'role' => 'kepala_sekolah',
]);

User::create([
'name' => 'Bendahara',
'email' => 'bendahara@example.com',
'password' => Hash::make('password'),
'role' => 'bendahara',
]);
}
}