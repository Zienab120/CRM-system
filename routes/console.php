<?php

use App\Jobs\GenerateUserEmailsJob;
use App\Jobs\ScheduleCampaign;
use Illuminate\Support\Facades\Schedule;

Schedule::job(GenerateUserEmailsJob::class)->everyFiveMinutes();
Schedule::job(ScheduleCampaign::class)->daily();
