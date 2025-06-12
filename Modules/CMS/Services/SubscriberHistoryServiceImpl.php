<?php

namespace Modules\CMS\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Modules\CMS\Contracts\Repositories\SubscriberHistoryRepository;
use Modules\CMS\Contracts\Services\SubscriberHistoryService;

readonly class SubscriberHistoryServiceImpl implements SubscriberHistoryService
{
    public function __construct(
        private SubscriberHistoryRepository $subscriberHistoryRepository
    ) {}

    /**
     * @return Collection
     */
    public function getAll(): Collection
    {
        $condition = new Request(['sort' => '-id']);

        return $this->subscriberHistoryRepository->handle($condition)->get();
    }
}
