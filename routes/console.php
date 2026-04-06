<?php

use App\Jobs\GenerateUserEmailsJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(GenerateUserEmailsJob::class)->everyFiveMinutes();
