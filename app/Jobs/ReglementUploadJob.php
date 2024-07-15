<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ReglementUploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $file;
    private $filename;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file,$filename)
    {
        $this->file = $file;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* Uploader les documents dans la base de données */
        Storage::disk('public')->path($this->filename);
        //$this->file->storeAs('documents', $this->filename, 'public');
    }
}
