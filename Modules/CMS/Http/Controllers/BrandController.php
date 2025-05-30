<?php

namespace Modules\CMS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Brand\BrandCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\CMS\Contracts\Services\BrandService;
use Modules\CMS\Http\Requests\Brand\StoreRequest;

class BrandController extends Controller
{
    public function __construct(
        private readonly BrandService $brandService
    ){
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $brands = $this->brandService->getAllBrands();

        return Inertia::render('brands/Index', [
            'brands' => BrandCollection::make($brands)
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('brands/Create');
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->brandService->store($request);

        return to_route('brands.index');
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
