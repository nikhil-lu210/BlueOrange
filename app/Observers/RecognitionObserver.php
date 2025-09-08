<?php

namespace App\Observers;

use App\Models\Recognition\Recognition;
use App\Services\Administration\Recognition\RecognitionService;
use App\Models\User;

class RecognitionObserver
{
    public function created(Recognition $recognition)
    {
        $recognition->load(['user', 'recognizer']);
        $service = new RecognitionService();

        $employee = User::find($recognition->user_id);

        $service->sendCongratulation($employee, $recognition);
        $service->allUserNotify($employee, $recognition);
    }
}
