<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportLeadsJob implements ShouldQueue
{
    use Queueable, Dispatchable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private $filePath, private $userID) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fullPath = storage_path('app/' . $this->filePath);
        $initiator = User::find($this->userID);

        if (!file_exists($fullPath)) return;

        SimpleExcelReader::create($fullPath)
            ->getRows()->chunk(100)->each(function ($chunk) use ($initiator) {
                DB::transaction(function () use ($chunk, $initiator) {
                    foreach ($chunk as $row) {
                        User::create([
                            'name' => $row['contact_name'],
                            'email' => $row['contact_email'],
                            'phone' => $row['contact_phone'],
                        ]);

                        $lead = Lead::create([
                            'company_id' => $row['company_id'],
                            'assigned_by' => $this->userID,
                            'assigned_to' => $row['assigned_to'],
                            'status' => $row['status'] ?? 'new',
                            'source' => $row['source'],
                            'score' => $row['score'],
                            'notes' => $row['notes'],
                        ]);

                        $chunk['assigned_to'] ? AssignLeadToSalesRep::dispatch($lead, $chunk['assigned_to'], $initiator)->afterCommit() : null;
                    }
                });
            });

        Storage::delete($this->filePath);
    }
}
