<?php

namespace App\Console\Commands;

use App\Services\OverdueBillService;
use Illuminate\Console\Command;

class CancelOverdueBills extends Command
{
    protected $signature = 'app:cancel-overdue-bills';

    protected $description = 'Batalkan otomatis tagihan yang sudah melewati jatuh tempo';

    public function handle(OverdueBillService $overdueBillService): int
    {
        $cancelled = $overdueBillService->cancelOverdueBills();

        $this->info("{$cancelled} tagihan kedaluwarsa dibatalkan.");

        return self::SUCCESS;
    }
}
