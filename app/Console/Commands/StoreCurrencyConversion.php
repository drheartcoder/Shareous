<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StoreCurrencyConversion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store_currency_conversion:schedule';
   
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $service_class = \App\Common\Services\Cron\CronService::class;
        $service_class = app($service_class);
        $service_class->store_currency_conversion();

    }
}