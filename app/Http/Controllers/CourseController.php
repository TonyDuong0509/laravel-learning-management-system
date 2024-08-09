<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Course_goal;
use App\Models\CourseLecture;
use App\Models\CourseSection;
use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

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

    public function GetSubCategory($category_id)
    {
        $subcategory = SubCategory::where('category_id', '=', $category_id)->orderBy('subcategory_name', 'ASC')->get();
        return json_encode($subcategory);
    }

    public function StoreCourse(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4|max:10000',
        ]);

        $image = $request->file('course_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(370, 246)->save('upload/course/thumbnail/' . $name_gen);
        $save_url = 'upload/course/thumbnail/' . $name_gen;

        $video = $request->file('video');
        $videoName = time() . '.' . $video->getClientOriginalExtension();
        $video->move(public_path('upload/course/video/'), $videoName);
        $save_video = 'upload/course/video/' . $videoName;

        $course_id = Course::insertGetId([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'instructor_id' => Auth::user()->id,
            'course_title' => $request->course_title,
            'course_name' => $request->course_name,
            'course_name_slug' => strtolower(str_replace(' ', '-', $request->course_name)),
            'description' => $request->description,
            'video' => $save_video,

            'label' => $request->label,
            'duration' => $request->duration,
            'resources' => $request->resources,
            'certificate' => $request->certificate,
            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'prerequisites' => $request->prerequisites,

            'bestseller' => $request->bestseller,
            'featured' => $request->featured,
            'highestrated' => $request->highestrated,
            'status' => 1,
            'course_image' => $save_url,
            'created_at' => Carbon::now(),
        ]);

        $goals = Count($request->course_goals);
        if ($goals != null) {
            for ($i = 0; $i < $goals; $i++) {
                $gcount = new Course_goal();
                $gcount->course_id = $course_id;
                $gcount->goal_name = $request->course_goals[$i];
                $gcount->save();
            }
        }

        $notification = array(
            'message' => 'Course Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.course')->with($notification);
    }

    public function EditCourse($id)
    {
        $course = Course::find($id);
        $categories = Category::latest()->get();
        $subcategories = SubCategory::latest()->get();
        $goals = Course_goal::where('course_id', '=', $id)->get();

        return view('instructor.course.edit_course', compact(
            'course',
            'categories',
            'subcategories',
            'goals'
        ));
    }

    public function UpdateCourse(Request $request)
    {
        $cid = $request->course_id;

        Course::find($cid)->update([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'instructor_id' => Auth::user()->id,
            'course_name' => $request->course_name,
            'course_name_slug' => strtolower(str_replace(' ', '-', $request->course_name)),
            'course_title' => $request->course_title,
            'certificate' => $request->certificate,
            'label' => $request->label,
            'selling_price' => $request->selling_price,
            'discount_price' => $request->discount_price,
            'duration' => $request->duration,
            'resources' => $request->resources,
            'prerequisites' => $request->prerequisites,
            'description' => $request->description,
            'bestseller' => $request->bestseller,
            'featured' => $request->featured,
            'highestrated' => $request->highestrated,
        ]);

        $notification = array(
            'message' => 'Course Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.course')->with($notification);
    }

    public function UpdateCourseImage(Request $request)
    {
        $course_id = $request->id;
        $oldImage = $request->old_img;

        $image = $request->file('course_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(370, 246)->save('upload/course/thumbnail/' . $name_gen);
        $save_url = 'upload/course/thumbnail/' . $name_gen;

        if (file_exists($oldImage)) {
            unlink($oldImage);
        }

        Course::find($course_id)->update([
            'course_image' => $save_url,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Course Image Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function UpdateCourseVideo(Request $request)
    {
        $course_id = $request->id;
        $old_video = $request->old_video;

        $video = $request->file('video');
        $videoName = time() . '.' . $video->getClientOriginalExtension();
        $video->move(public_path('upload/course/video/'), $videoName);
        $save_video = 'upload/course/video/' . $videoName;

        if (file_exists($old_video)) {
            unlink($old_video);
        }

        Course::find($course_id)->update([
            'video' => $save_video,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Course Video Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function UpdateCourseGoal(Request $request)
    {
        $course_id = $request->course_id;

        if ($request->course_goals === null) {
            return redirect()->back();
        } else {
            Course_goal::where('course_id', '=', $course_id)->delete();

            $goals = Count($request->course_goals);

            for ($i = 0; $i < $goals; $i++) {
                $gcount = new Course_goal();
                $gcount->course_id = $course_id;
                $gcount->goal_name = $request->course_goals[$i];
                $gcount->save();
            }
        }

        $notification = array(
            'message' => 'Course Goals Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function DeleteCourse($id)
    {
        $course = Course::find($id);
        unlink($course->course_image);
        unlink($course->video);
        Course::find($id)->delete();

        $goalsData = Course_goal::where('course_id', '=', $id)->get();
        foreach ($goalsData as $goal) {
            Course_goal::where('course_id', '=', $id)->delete();
        }

        $notification = array(
            'message' => 'Course Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function AddCourseLecture($id)
    {
        $course = Course::find($id);

        $section = CourseSection::where('course_id', '=', $id)->latest()->get();

        return view('instructor.course.section.add_course_lecture', compact(
            'course',
            'section',
        ));
    }

    public function AddCourseSection(Request $request)
    {
        $course_id = $request->id;

        CourseSection::insert([
            'course_id' => $course_id,
            'section_title' => $request->section_title,
        ]);

        $notification = array(
            'message' => 'Course Section Added Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function SaveLecture(Request $request)
    {
        $lecture = new CourseLecture();
        $lecture->course_id = $request->course_id;
        $lecture->section_id = $request->section_id;
        $lecture->lecture_title = $request->lecture_title;
        $lecture->url = $request->lecture_url;
        $lecture->content = $request->content;
        $lecture->save();

        return response()->json(['Success' => 'Lecture saved successfully !']);
    }

    public function EditLecture($id)
    {
        $c_lecture = CourseLecture::find($id);

        return view('instructor.course.lecture.edit_course_lecture', compact(
            'c_lecture',
        ));
    }

    public function UpdateCourseLecture(Request $request)
    {
        $id = $request->id;

        CourseLecture::find($id)->update([
            'lecture_title' => $request->lecture_title,
            'url' => $request->url,
            'content' => $request->content,
        ]);

        $notification = array(
            'message' => "Course Lecture updated successfully",
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }

    public function DeleteLecture($id)
    {
        CourseLecture::find($id)->delete();

        $notification = array(
            'message' => "Deleted successfully",
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }

    public function DeleteSection($id)
    {
        $section = CourseSection::find($id);
        if ($section) {
            $section->lectures()->delete();

            $section->delete();
        }

        $notification = array(
            'message' => "Deleted successfully",
            'alert-type' => 'success',
        );

        return redirect()->back()->with($notification);
    }
}
