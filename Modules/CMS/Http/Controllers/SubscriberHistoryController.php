<?php

namespace Modules\CMS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriberHistory\SubscriberHistoryCollection;
use Inertia\Inertia;
use Inertia\Response;
use Modules\CMS\Contracts\Services\SubscriberHistoryService;

class SubscriberHistoryController extends Controller
{
    public function __construct(
        private readonly SubscriberHistoryService $subscriberHistoryService
    ){
    }
    /**
     * @return Response
     */
    public function index(): Response
    {
        $subscriberHistories = $this->subscriberHistoryService->getAll();

        return Inertia::render('subscriberHistories/Index', [
            'subscriberHistories' => SubscriberHistoryCollection::make($subscriberHistories)
        ]);
    }
}
