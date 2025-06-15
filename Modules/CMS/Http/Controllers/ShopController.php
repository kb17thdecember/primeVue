<?php

namespace Modules\CMS\Http\Controllers;

use App\Enums\ShopStatus;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Modules\CMS\Contracts\Services\AdminService;
use Modules\CMS\Contracts\Services\MailService;
use Modules\CMS\Contracts\Services\ShopService;
use Modules\CMS\Http\Requests\Shop\UpdateKeyRequest;
use Modules\CMS\Http\Requests\Shop\StoreRequest;
use Modules\CMS\Http\Requests\Shop\UpdateRequest;

class ShopController extends Controller
{
    public function __construct(
        private readonly ShopService $shopService,
        private readonly AdminService $adminService,
        private readonly MailService $mailService
    ){}

    /**
     * @return Response
     */
    public function index(): Response
    {
        $shops = $this->shopService->getAllShops();

        return Inertia::render('shops/Index', [
            'shops' => $shops,
        ]);
    }

    public function getInfo(Request $request): JsonResponse
    {
        $apiKey = $request->header('X-Shop-Api-Key');

        $shop = Shop::query()
            ->where('api_key', $apiKey)
            ->where('status', ShopStatus::ACTIVE->value)
            ->first();

        if (!$shop) {
            return response()->json([
                'message' => 'Shop not found',
            ], 404);
        }

        return response()->json([
            'name' => $shop->name,
            'address' => $shop->address,
            'phone_number' => $shop->phone_number,
            'current_token_qty' => $shop->current_token_qty,
            'token_expired_date' => $shop->token_expired_date,
        ], 200);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('shops/Create', [
            'admin' => Auth::user()
        ]);
    }

    /**
     * @param StoreRequest $request
     * @param \Modules\CMS\Http\Requests\Admin\StoreRequest $adminRequest
     * @return RedirectResponse
     */
    public function store(StoreRequest $request, \Modules\CMS\Http\Requests\Admin\StoreRequest $adminRequest): RedirectResponse
    {
        $shopStore = $this->shopService->create($request);
        $shopId = $shopStore->id;
        $admin = $this->adminService->store($adminRequest, $shopId);
        $shopStore->update(['admin_id' => $admin->id]);

        return to_route('shops.index');
    }

    /**
     * @return Response
     */
    public function showKey(): Response
    {
        $shop = $this->shopService->show();

        return Inertia::render('shops/RequestKey',[
            'shop' => $shop,
        ]);
    }

    /**
     * @return Response
     */
    public function show(): Response
    {
        $shop = $this->shopService->show();

        return Inertia::render('shops/UpdateKey', [
            'shop' => $shop
        ]);
    }

    /**
     * @param int $shop
     * @return Response
     */
    public function edit(int $shop): Response
    {
        $data = $this->shopService->edit($shop);

        return Inertia::render('shops/Update', [
            'shop' => $data
        ]);
    }

    /**
     * @param UpdateRequest $request
     * @param int $shop
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, int $shop): RedirectResponse
    {
        $this->shopService->update($request, $shop);

        return to_route('shops.index');
    }

    /**
     * @return RedirectResponse
     */
    public function updateStatus(): RedirectResponse
    {
        $this->shopService->updateRequestKey();
        $this->mailService->send([
            'to' => 'thanh98nv@gmail.com',
            'subject' => 'Request Key Updated',
            'content' => "A new request key update API Key has been submitted.\n\n"
                . "Shop: " . Auth::user()->name . "\n"
                . "Email: " . Auth::user()->email . "\n"
                . "Requested at: " . now()->format('Y-m-d H:i:s'),
        ]);

        return to_route('shops.key.edit');
    }

    /**
     * @param UpdateKeyRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function updateApiKey(UpdateKeyRequest $request, $id): RedirectResponse
    {
            $this->shopService->updateApiKey($request, $id);

            return to_route('shops.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
