<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function AllCourse()
    {
        $id = Auth::user()->id;
        $courses = Course::where('instructor_id', '=', $id)->orderBy('id', 'desc')->get();

        return view('instructor.course.all_course', compact(
            'courses',
        ));
    }

    public function AddCourse()
    {
        $categories = Category::latest()->get();

        return view('instructor.course.add_course', compact(
            'categories',
        ));
    }

    public function StoreCourse(Request $request)
    {
        // $request->validate([
        //     ''
        // ]);
    }
}
