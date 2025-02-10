<?php

namespace Modules\Category\src\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\Category\src\Http\Requests\CategoryRequest;
use Modules\Category\src\Repositories\CategoryRepository;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $pageTitle = 'Quản Lý Danh Mục';

        return view('category::lists', compact('pageTitle'));
    }

    public function data()
    {
        $categories = $this->categoryRepository->getCategories();

        $categories = DataTables::of($categories)
            // ->addColumn('edit', function ($category) {
            //     return '<a href="' . route('admin.categories.edit', $category) . '" class="btn btn-warning">Sửa</a>';
            // })
            // ->addColumn('delete', function ($category) {
            //     return '<a href="' . route('admin.categories.delete', $category) . '" class="btn btn-danger delete-action">Xoá</a>';
            // })
            // ->addColumn('link', function ($category) {
            //     return '<a href="" class="btn btn-primary">Xem</a>';
            // })
            // ->editColumn('created_at', function ($category) {
            //     return Carbon::parse($category->created_at)->format('d/m/Y H:i:s');
            // })
            // ->rawColumns(['edit', 'delete', 'link'])
            ->toArray();

        $categories['data'] = $this->getCategoriesTable($categories['data']);

        return $categories;
    }

    private function getCategoriesTable($categories, $char = '', &$result = [])
    {
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $row = $category;
                $row['name'] = $char . $row['name'];
                $row['edit'] = '<a href="' . route('admin.categories.edit', $category['id']) . '" class="btn btn-warning">Sửa</a>';
                $row['delete'] = '<a href="' . route('admin.categories.delete', $category['id']) . '" class="btn btn-danger delete-action">Xoá</a>';
                $row['link'] = '<a target="_blank" href="/danh-muc/' . $category['slug'] . '" class="btn btn-primary">Xem</a>';
                $row['created_at'] = Carbon::parse($category['created_at'])->format('d/m/Y H:i:s');
                unset($row['sub_categories']);
                unset($row['updated_at']);
                $result[] = $row;
                if (!empty($category['sub_categories'])) {
                    $this->getCategoriesTable($category['sub_categories'], $char . '|--', $result);
                }
            }
        }
        return $result;
    }

    public function create()
    {
        $pageTitle = 'Thêm Danh Mục';

        $categories = $this->categoryRepository->getAllCategories();

        return view('category::add', compact('pageTitle', 'categories'));
    }

    public function store(CategoryRequest $request)
    {
        $attributes = [
            'name' => $request->name,
            'slug' => $request->slug,
            'parent_id' => $request->parent_id,
        ];

        $this->categoryRepository->create($attributes);

        return redirect()->route('admin.categories.index')->with('msg', __('category::messages.create.success'));
    }

    public function edit($id)
    {
        $pageTitle = 'Cập Nhật Người Dùng';
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            abort(404);
        }

        $categories = $this->categoryRepository->getAllCategories();

        return view('category::edit', compact('pageTitle', 'category', 'categories'));
    }

    public function update(CategoryRequest $request, $id)
    {
        $data = $request->except('_token');

        $this->categoryRepository->update($id, $data);

        return redirect()->back()->with('msg', __('category::messages.update.success'));
    }

    public function delete($id)
    {
        $this->categoryRepository->delete($id);
        return redirect()->back()->with('msg', __('category::messages.delete.success'));
    }
}
