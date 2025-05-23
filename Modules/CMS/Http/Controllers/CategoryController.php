<?php

namespace Modules\CMS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\CMS\Contracts\Services\CategoryService;
use Modules\CMS\Http\Requests\Category\StoreRequest;

class CategoryController extends Controller
{
    private $rootViewPath = 'categories';

    public function __construct(
        private readonly CategoryService $categoryService
    ){
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $categories = $this->categoryService->getAllCategories($request);

        return Inertia::render("$this->rootViewPath/Index", [
            'categories' => CategoryCollection::make($categories)
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render("$this->rootViewPath/Create");
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->categoryService->storeCategory($request);

        return to_route('categories.index');
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
