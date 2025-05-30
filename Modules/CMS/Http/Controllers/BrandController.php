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
use Modules\CMS\Http\Requests\Brand\UpdateRequest;

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
     * @param int $brand
     * @return Response
     */
    public function edit(int $brand): Response
    {
        $brandEdit = $this->brandService->edit($brand);

        return Inertia::render("brands/Update", [
            'brand' => $brandEdit,
        ]);
    }

    /**
     * @param UpdateRequest $request
     * @param int $brand
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, int $brand): RedirectResponse
    {
        $this->brandService->update($brand, $request);

        return to_route('brands.index');
    }

    /**
     * @param int $brand
     * @return RedirectResponse
     */
    public function destroy(int $brand): RedirectResponse
    {
        $this->brandService->delete($brand);

        return to_route('brands.index');
    }
}
