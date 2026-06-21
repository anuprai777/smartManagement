<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RegenerateQrCodes extends Command
{
    protected $signature = 'tickets:regenerate-qr {--force : Regenerate QR codes even for tickets that already have one}';
    protected $description = 'Regenerate missing QR code images for all tickets';

    public function handle(): int
    {
        $query = Ticket::whereNull('qr_code_path')
            ->orWhere('qr_code_path', '');

        if ($this->option('force')) {
            $query = Ticket::whereNotNull('id');
        }

        $tickets = $query->get();
        $count = 0;

        foreach ($tickets as $ticket) {
            if (!$ticket->qr_code_data) {
                $this->warn("Ticket #{$ticket->id} ({$ticket->ticket_number}) has no QR data — skipping.");
                continue;
            }

            try {
                $result = (new Builder(
                    writer: new PngWriter(),
                    data: $ticket->qr_code_data,
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 300,
                    margin: 10,
                ))->build();

                $filename = 'qr-codes/' . $ticket->ticket_number . '.png';
                Storage::disk('public')->put($filename, $result->getString());

                $ticket->update(['qr_code_path' => $filename]);
                $this->info("Generated QR code for ticket #{$ticket->id} ({$ticket->ticket_number})");
                $count++;
            } catch (\Exception $e) {
                $this->error("Failed for ticket #{$ticket->id} ({$ticket->ticket_number}): " . $e->getMessage());
            }
        }

        $this->info("Done! Generated {$count} QR code(s).");

        return self::SUCCESS;
    }
}
