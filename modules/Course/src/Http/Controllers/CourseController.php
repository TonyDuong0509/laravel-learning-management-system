<?php

namespace Modules\Course\src\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\FileUploadTrait;
use Carbon\Carbon;
use Modules\Category\src\Repositories\CategoryRepository;
use Modules\Course\src\Http\Requests\CourseRequest;
use Modules\Course\src\Repositories\CourseRepository;
use Yajra\DataTables\DataTables;

class CourseController extends Controller
{
    use FileUploadTrait;

    private $courseRepository;
    private $categoryRepository;

    public function __construct(
        CourseRepository $courseRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->courseRepository = $courseRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $pageTitle = 'Quản Lý Khoá Học';
        return view('course::lists', compact('pageTitle'));
    }

    public function data()
    {
        $courses = $this->courseRepository->getAllCourses();

        return DataTables::of($courses)
            ->addColumn('edit', function ($course) {
                return '<a href="' . route('admin.courses.edit', $course) . '" class="btn btn-warning">Sửa</a>';
            })
            ->addColumn('delete', function ($course) {
                return '<a href="' . route('admin.courses.delete', $course) . '" class="btn btn-danger delete-action">Xoá</a>';
            })
            ->editColumn('created_at', function ($course) {
                return Carbon::parse($course->created_at)->format('d/m/Y H:i:s');
            })
            ->editColumn('status', function ($course) {
                return $course->status == 1 ? '<button class="btn btn-success">Đã ra mắt</button>' : '<button class="btn btn-primary">Chưa ra mắt</button>';
            })
            ->editColumn('price', function ($course) {
                if ($course->price) {
                    if ($course->sale_price) {
                        return number_format($course->sale_price) . 'VNĐ';
                    } else {
                        return number_format($course->price) . 'VNĐ';
                    }
                } else {
                    return 'Miễn phí';
                }
            })
            ->rawColumns(['edit', 'delete', 'status', 'price'])
            ->toJson();
    }

    public function create()
    {
        $pageTitle = 'Thêm Khoá Học';

        $categories = $this->categoryRepository->getAllCategories();

        return view('course::add', compact('pageTitle', 'categories'));
    }

    public function store(CourseRequest $request)
    {
        $courses = $request->except(['_token']);
        $imagePath = $this->uploadImage($request, 'thumbnail', 'course');
        if ($imagePath) $courses['thumbnail'] = $imagePath;
        if (!$courses['sale_price']) $courses['sale_price'] = 0;
        if (!$courses['price']) $courses['price'] = 0;

        $course = $this->courseRepository->create($courses);

        $categories = $this->getCategories($course);

        $this->courseRepository->createCourseCategories($course, $categories);

        return redirect()->route('admin.courses.index')->with('msg', __('course::messages.create.success'));
    }

    public function edit($id)
    {
        $pageTitle = 'Cập Nhật Khoá Học';

        $categories = $this->categoryRepository->getAllCategories();

        $course = $this->courseRepository->find($id);
        if (!$course) {
            abort(404);
        }

        $categoryIds = $this->courseRepository->getRelatedCategories($course);

        return view('course::edit', compact('pageTitle', 'course', 'categories', 'categoryIds'));
    }

    public function update(CourseRequest $request, $id)
    {
        $course = $request->except(['_token', '_method']);
        $imagePath = $this->uploadImage($request, 'thumbnail', 'course', $course['old_image']);
        if ($imagePath) $course['thumbnail'] = $imagePath;
        if (!$course['sale_price']) $course['sale_price'] = 0;
        if (!$course['price']) $course['price'] = 0;

        $this->courseRepository->update($id, $course);

        $categories = $this->getCategories($course);

        $courseParam = $this->courseRepository->find($id);
        $this->courseRepository->updateCourseCategories($courseParam, $categories);

        return redirect()->back()->with('msg', __('course::messages.update.success'));
    }

    public function delete($id)
    {
        $course = $this->courseRepository->find($id);
        $this->courseRepository->deleteCourseCategories($course);
        $this->courseRepository->delete($id);
        return redirect()->back()->with('msg', __('course::messages.delete.success'));
    }

    private function getCategories($courses)
    {
        $categories = [];
        foreach ($courses['categories'] as $category) {
            $categories[$category] = ['created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')];
        };

        return $categories;
    }
}
