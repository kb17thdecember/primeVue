<?php

namespace Modules\CMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Modules\CMS\Contracts\Services\ShopService;
use Modules\CMS\Http\Requests\Shop\StoreRequest;

class ShopController extends Controller
{
    public function __construct(
        private readonly ShopService $shopService
    ){}

    /**
     * @return Response
     */
    public function index(): Response
    {
        $shops = $this->shopService->getAllShops();
        return Inertia::render('shops/Index', [
            'shops' => $shops,
            'auth' => [
                'user' => Auth::guard('admin')->user()
            ]
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('shops/Create', [
            'auth' => [
                'user' => Auth::guard('admin')->user()
            ]
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->shopService->create($request);

        return to_route('shops.index');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('cms::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('cms::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
