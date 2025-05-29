<?php

namespace Modules\CMS\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryCollection;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Modules\CMS\Contracts\Services\CategoryService;
use Modules\CMS\Http\Requests\Category\StoreRequest;
use Modules\CMS\Http\Requests\Category\UpdateRequest;

class CategoryController extends Controller
{
    private $rootViewPath = 'categories';

    public function __construct(
        private readonly CategoryService $categoryService
    ){
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $categories = $this->categoryService->getAllCategories();

        return Inertia::render("$this->rootViewPath/Index", [
            'categories' => CategoryCollection::make($categories)
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        $parents = $this->categoryService->getParent();

        return Inertia::render("$this->rootViewPath/Create", [
            'parents' => $parents
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->categoryService->store($request);

        return to_route('categories.index')
            ->with('toast', [
                'severity' => 'success',
                'summary' => 'Success',
                'detail' => 'Create Category Success',
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('cms::show');
    }

    /**
     * @param int $category
     * @return Response
     */
    public function edit(int $category): Response
    {
        $categoryEdit = $this->categoryService->edit($category);

        return Inertia::render("$this->rootViewPath/Update", [
            'category' => $categoryEdit,
        ]);
    }

    /**
     * @param UpdateRequest $request
     * @param int $category
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, int $category): RedirectResponse
    {
        $this->categoryService->update($category, $request);

        return to_route('categories.index')
            ->with('toast', [
                'severity' => 'success',
                'summary' => 'Success',
                'detail' => 'Update Category Success',
            ]);
    }

    /**
     * @param int $category
     * @return RedirectResponse
     */
    public function destroy(int $category): RedirectResponse
    {
        $this->categoryService->delete($category);

        return to_route('categories.index');
    }
}
