<?php

namespace Database\Seeders;

use App\Models\BudgetAllocation;
use App\Models\Category;
use App\Models\Expense;
use App\Models\RecurringExpense;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoAccountSeeder extends Seeder
{
    public const DEMO_EMAIL = 'demo@titik-simpan.app';

    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => self::DEMO_EMAIL],
            [
                'name' => 'Demo',
                'password' => Hash::make(Str::password(20)),
            ]
        );

        Auth::loginUsingId($user->id);

        CategorySeeder::seedFor($user);

        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $month = $monthStart->format('Y-m');

        Expense::query()->delete();
        BudgetAllocation::query()->delete();
        Salary::query()->delete();
        RecurringExpense::query()->delete();

        $salary = Salary::updateOrCreate(
            ['received_at' => $monthStart->toDateString()],
            ['amount' => 8500000, 'note' => 'Gaji '.$monthStart->translatedFormat('F Y').' (demo)']
        );

        $plan = [
            'Kebutuhan Pokok' => 3000000,
            'Transport' => 1000000,
            'Tabungan' => 1500000,
            'Hiburan' => 600000,
            'Tagihan & Utilitas' => 1500000,
            'Lainnya' => 400000,
        ];

        $allocs = [];
        foreach ($plan as $name => $amount) {
            $category = Category::where('name', $name)->first();
            if (! $category) {
                continue;
            }
            $allocs[$name] = BudgetAllocation::updateOrCreate(
                ['salary_id' => $salary->id, 'category_id' => $category->id],
                ['amount' => $amount]
            );
        }

        $daysInMonth = $monthStart->daysInMonth;
        $spends = [
            ['Kebutuhan Pokok', 'Belanja pasar mingguan', 250000],
            ['Transport', 'Isi bensin', 150000],
            ['Hiburan', 'Streaming bulanan', 75000],
            ['Lainnya', 'Potong rambut', 50000],
            ['Kebutuhan Pokok', 'Belanja pasar', 280000],
            ['Tagihan & Utilitas', 'Pulsa & paket data', 100000],
            ['Transport', 'Gojek kerja', 25000],
            ['Hiburan', 'Nonton bioskop', 90000],
            ['Kebutuhan Pokok', 'Belanja mingguan', 260000],
            ['Lainnya', 'Perlengkapan rumah', 75000],
            ['Tagihan & Utilitas', 'Listrik & air', 350000],
            ['Transport', 'Isi bahan bakar', 210000],
            ['Kebutuhan Pokok', 'Belanja pasar', 310000],
            ['Hiburan', 'Makan di luar', 130000],
            ['Kebutuhan Pokok', 'Belanja kebutuhan dapur', 175000],
            ['Tagihan & Utilitas', 'Internet bulanan', 150000],
            ['Transport', 'Parkir & tol', 60000],
            ['Kebutuhan Pokok', 'Belanja mingguan', 330000],
            ['Hiburan', 'Game & top up', 100000],
            ['Tagihan & Utilitas', 'Cicilan gadget', 450000],
            ['Kebutuhan Pokok', 'Belanja pasar', 295000],
            ['Transport', 'Isi ulang bensin', 175000],
            ['Tagihan & Utilitas', 'Listrik token', 200000],
            ['Kebutuhan Pokok', 'Belanja harian', 140000],
        ];

        $count = 0;
        foreach (array_slice($spends, 0, $daysInMonth) as $i => $spend) {
            [$categoryName, $desc, $amount] = $spend;
            $day = min($i + 1, $daysInMonth);
            $alloc = $allocs[$categoryName] ?? null;
            $cat = Category::where('name', $categoryName)->first();
            if (! $cat) {
                continue;
            }

            Expense::create([
                'category_id' => $cat->id,
                'budget_allocation_id' => $alloc ? $alloc->id : null,
                'amount' => $amount,
                'description' => $desc,
                'spent_at' => $month.'-'.str_pad($day, 2, '0', STR_PAD_LEFT),
                'is_recurring' => false,
            ]);

            if ($alloc) {
                $alloc->increment('spent', $amount);
            }
            $count++;
        }

        $recurring = [
            ['Kebutuhan Pokok', 'Belanja bulanan', 1500000, 'monthly'],
            ['Tagihan & Utilitas', 'Listrik & internet', 500000, 'monthly'],
            ['Tagihan & Utilitas', 'Internet fiber', 300000, 'monthly'],
            ['Tagihan & Utilitas', 'BPJS Kesehatan', 210000, 'monthly'],
            ['Hiburan', 'Streaming (Netflix+Spotify)', 200000, 'monthly'],
            ['Transport', 'Bensin mingguan', 200000, 'weekly'],
            ['Lainnya', 'Cicilan gadget', 450000, 'monthly'],
        ];

        foreach ($recurring as [$categoryName, $name, $amount, $freq]) {
            $cat = Category::where('name', $categoryName)->first();
            if (! $cat) {
                continue;
            }

            RecurringExpense::updateOrCreate(
                ['category_id' => $cat->id, 'name' => $name],
                [
                    'amount' => $amount,
                    'frequency' => $freq,
                    'next_due_date' => $now->copy()->addDays(3)->toDateString(),
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Akun demo ('.$user->email.') siap: gaji Rp8.500.000 + '.$count.' pengeluaran + '.count($recurring).' tagihan berulang.');
    }
}
