<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // LIST PAGE
    public function index(Request $request)
    {
        $students = DB::table('students')

            // Search
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })

            // Min Age Filter
            ->when($request->min_age, function ($query) use ($request) {
                $query->where('age', '>=', $request->min_age);
            })

            // Max Age Filter
            ->when($request->max_age, function ($query) use ($request) {
                $query->where('age', '<=', $request->max_age);
            })

            // Sorting
            ->when($request->sort == 'age_asc', function ($query) {
                $query->orderBy('age', 'asc');
            })

            ->when($request->sort == 'age_desc', function ($query) {
                $query->orderBy('age', 'asc');
            })

            ->paginate(4)
            ->withQueryString();

        // Dashboard Statistics
        $totalStudents = DB::table('students')->count();
        $averageAge = DB::table('students')->avg('age');
        $youngest = DB::table('students')->min('age');
        $oldest = DB::table('students')->max('age');

        return view('students.index', compact(
            'students',
            'totalStudents',
            'averageAge',
            'youngest',
            'oldest'
        ));
    }

    // CREATE FORM
    public function create()
    {
        return view('students.create');
    }

    // STORE DATA
    public function store(Request $request)
    {
        DB::table('students')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'age' => $request->age,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/students')
            ->with('success', 'Student added successfully');
    }

    // STUDENT DETAILS
    public function show($id)
    {
        $student = DB::table('students')
            ->where('id', $id)
            ->first();

        return view('students.show', compact('student'));
    }

    // EDIT FORM
    public function edit($id)
    {
        $student = DB::table('students')
            ->where('id', $id)
            ->first();

        return view('students.edit', compact('student'));
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        DB::table('students')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'age' => $request->age,
                'updated_at' => now(),
            ]);

        return redirect('/students')
            ->with('success', 'Student updated successfully');
    }

    // DELETE
    public function delete($id)
    {
        DB::table('students')
            ->where('id', $id)
            ->delete();

        return redirect('/students')
            ->with('success', 'Student deleted successfully');
    }

    // EXPORT CSV
    public function export(Request $request)
    {
        $students = DB::table('students')

            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })

            ->when($request->min_age, function ($query) use ($request) {
                $query->where('age', '>=', $request->min_age);
            })

            ->when($request->max_age, function ($query) use ($request) {
                $query->where('age', '<=', $request->max_age);
            })

            ->when($request->sort == 'age_asc', function ($query) {
                $query->orderBy('age', 'asc');
            })

            ->when($request->sort == 'age_desc', function ($query) {
                $query->orderBy('age', 'desc');
            })

            ->get();

        $fileName = 'students.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($students) {

            $file = fopen('php://output', 'w');

            fputcsv($file, ['ID', 'Name', 'Email', 'Age']);

            foreach ($students as $student) {
                fputcsv($file, [
                    $student->id,
                    $student->name,
                    $student->email,
                    $student->age,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}