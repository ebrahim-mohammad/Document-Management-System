<?php

namespace App\Observers;

use App\Models\Document;
use Illuminate\Support\Facades\Log;

class DocumentObserver
{
    /**
     * Handle the Document "created" event.
     */
    public function created(Document $document): void
    {
        Log::info('Document Created: ', ['document' => $document]);

        // $document->user->notify('Document Created: ', ['document' => $document]);


    }

    /**
     * Handle the Document "updated" event.
     */
    public function updated(Document $document): void
    {
        Log::info('Document Updated: ', ['document' => $document]);

    }

    /**
     * Handle the Document "deleted" event.
     */
    public function deleted(Document $document): void
    {
        Log::info('Document Deleted: ', ['document' => $document]);

    }

}
