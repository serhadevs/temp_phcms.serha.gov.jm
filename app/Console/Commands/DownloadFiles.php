<?php

namespace App\Console\Commands;

use App\Jobs\PermitJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DownloadFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zip_files:pull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        PermitJob::dispatch();
    }
}
