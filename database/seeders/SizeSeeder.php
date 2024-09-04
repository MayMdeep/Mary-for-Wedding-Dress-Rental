use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    public function run()
    {
        $sizes = ['Small', 'Medium', 'Large', 'X-Large'];

        foreach ($sizes as $size) {
            Size::create(['name' => $size]);
        }
    }
}
