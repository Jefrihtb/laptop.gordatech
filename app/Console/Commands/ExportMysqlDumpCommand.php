<?php

namespace App\Console\Commands;

use App\Services\SqlExportService;
use Illuminate\Console\Command;

class ExportMysqlDumpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cyber:export-sql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a MySQL/MariaDB compatible .sql dump for XAMPP phpMyAdmin and cPanel Shared Hosting';

    /**
     * Execute the console command.
     */
    public function handle(SqlExportService $exporter): int
    {
        $path = $exporter->saveDumpToFile();
        $this->info("MySQL dump generated successfully at: {$path}");

        return self::SUCCESS;
    }
}
