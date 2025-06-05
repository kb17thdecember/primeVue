<?php

namespace Modules\CMS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductCollection;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Modules\CMS\Contracts\Services\BrandService;
use Modules\CMS\Contracts\Services\CategoryService;
use Modules\CMS\Contracts\Services\ProductService;
use Modules\CMS\Http\Requests\Product\StoreRequest;
use Modules\CMS\Http\Requests\Product\UpdateRequest;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly CategoryService $categoryService,
        private readonly BrandService $brandService
    ){
    }
    /**
     * @return Response
     */
    public function index(): Response
    {
        $products = $this->productService->getAllProducts();

        return Inertia::render('products/Index', [
            'products' => ProductCollection::make($products)
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $categories = $this->categoryService->getParent();
        $brands = $this->brandService->getProductBrand();

        return Inertia::render('products/Create', [
            'categories' => $categories,
            'brands' => $brands
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->productService->store($request);

        return to_route('products.index');
    }

    /**
     * @param $product
     * @return Response
     */
    public function edit($product): Response
    {
        $productEdit = $this->productService->edit($product);
        $categories = $this->categoryService->getParent();
        $brands = $this->brandService->getProductBrand();

        return Inertia::render('products/Update', [
            'product' => $productEdit,
            'categories' => $categories,
            'brands' => $brands
        ]);
    }

    /**
     * @param UpdateRequest $request
     * @param int $product
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, int $product): RedirectResponse
    {
        $this->productService->update($product, $request);

        return to_route('products.index');
    }

    /**
     * @param $product
     * @return RedirectResponse
     */
    public function destroy($product): RedirectResponse
    {
        $this->productService->delete($product);

        return to_route('products.index');
    }
}
