<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\DB;

class UpdateBenchmarks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:benchmarks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::query()->delete();

        User::factory()->count(5000)->create();

        Benchmark::dd([
            'Regular update' => function () {
                for ($id = 1; $id <= 5000; $id++) {
                    User::whereId($id)
                        ->update(['email_verified_at' => now()]);
                }
            },
            'Using DB::transaction' => function () {
                DB::transaction(function () {
                    for ($id = 1; $id <= 5000; $id++) {
                        User::whereId($id)
                            ->update(['email_verified_at' => now()]);
                    }
                });
            },
        ], iterations: 5);
    }
}
