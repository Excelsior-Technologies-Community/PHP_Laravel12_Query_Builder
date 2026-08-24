<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? 10;

        $students = DB::table('students')
            ->when(!$request->trashed, function ($query) {
                $query->whereNull('deleted_at');
            })
            ->when($request->trashed, function ($query) {
                $query->whereNotNull('deleted_at');
            })

            // Search
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })

            // Name starting letter filter
            ->when($request->name_start, function ($query) use ($request) {
                $query->where('name', 'like', $request->name_start . '%');
            })

            // Age group filter
            ->when($request->age_group, function ($query) use ($request) {
                switch ($request->age_group) {
                    case 'under_15':
                        $query->where('age', '<', 15);
                        break;
                    case '15_18':
                        $query->whereBetween('age', [15, 18]);
                        break;
                    case '19_22':
                        $query->whereBetween('age', [19, 22]);
                        break;
                    case '23_25':
                        $query->whereBetween('age', [23, 25]);
                        break;
                    case 'above_25':
                        $query->where('age', '>', 25);
                        break;
                }
            })

            // Min Age Filter
            ->when($request->min_age, function ($query) use ($request) {
                $query->where('age', '>=', $request->min_age);
            })

            // Max Age Filter
            ->when($request->max_age, function ($query) use ($request) {
                $query->where('age', '<=', $request->max_age);
            })

            // Date range filter
            ->when($request->date_from, function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })

            // Sorting
            ->when($request->sort == 'age_asc', function ($query) {
                $query->orderBy('age', 'asc');
            })
            ->when($request->sort == 'age_desc', function ($query) {
                $query->orderBy('age', 'desc');
            })
            ->when($request->sort == 'name_asc', function ($query) {
                $query->orderBy('name', 'asc');
            })
            ->when($request->sort == 'name_desc', function ($query) {
                $query->orderBy('name', 'desc');
            })
            ->when($request->sort == 'date_asc', function ($query) {
                $query->orderBy('created_at', 'asc');
            })
            ->when($request->sort == 'date_desc', function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->when($request->sort == 'email_asc', function ($query) {
                $query->orderBy('email', 'asc');
            })
            ->when($request->sort == 'email_desc', function ($query) {
                $query->orderBy('email', 'desc');
            })

            ->paginate($perPage)
            ->withQueryString();

        // Dashboard Statistics
        $totalStudents = DB::table('students')->count();
        $averageAge = DB::table('students')->avg('age');
        $youngest = DB::table('students')->min('age');
        $oldest = DB::table('students')->max('age');
        $trashedCount = DB::table('students')->whereNotNull('deleted_at')->count();

        return view('students.index', compact(
            'students',
            'totalStudents',
            'averageAge',
            'youngest',
            'oldest',
            'trashedCount'
        ));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email',
            'age' => 'required|integer|min:1|max:120',
        ]);

        $emailExists = DB::table('students')
            ->where('email', $request->email)
            ->exists();

        if ($emailExists) {
            return back()->withInput()->with('error', 'Email already exists!');
        }

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

    public function show($id)
    {
        $student = DB::table('students')
            ->where('id', $id)
            ->first();

        return view('students.show', compact('student'));
    }

    public function edit($id)
    {
        $student = DB::table('students')
            ->where('id', $id)
            ->first();

        return view('students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students,email,' . $id,
            'age' => 'required|integer|min:1|max:120',
        ]);

        $emailExists = DB::table('students')
            ->where('email', $request->email)
            ->where('id', '!=', $id)
            ->exists();

        if ($emailExists) {
            return back()->withInput()->with('error', 'Email already exists for another student!');
        }

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

    public function delete($id)
    {
        DB::table('students')
            ->where('id', $id)
            ->update([
                'deleted_at' => now(),
                'updated_at' => now(),
            ]);

        return redirect('/students')
            ->with('success', 'Student moved to trash successfully');
    }

    public function restore($id)
    {
        DB::table('students')
            ->where('id', $id)
            ->update([
                'deleted_at' => null,
                'updated_at' => now(),
            ]);

        return redirect('/students/trash')
            ->with('success', 'Student restored successfully');
    }

    public function forceDelete($id)
    {
        DB::table('students')
            ->where('id', $id)
            ->delete();

        return redirect('/students/trash')
            ->with('success', 'Student permanently deleted');
    }

    public function trash(Request $request)
    {
        $students = DB::table('students')
            ->whereNotNull('deleted_at')

            // Search in trash
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })

            ->orderBy('deleted_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $trashedCount = DB::table('students')->whereNotNull('deleted_at')->count();

        return view('students.trash', compact('students', 'trashedCount'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:delete,export,restore',
            'student_ids' => 'required|array|min:1',
        ]);

        $ids = $request->student_ids;

        if ($request->action == 'delete') {
            DB::table('students')
                ->whereIn('id', $ids)
                ->update([
                    'deleted_at' => now(),
                    'updated_at' => now(),
                ]);

            return back()->with('success', 'Selected students moved to trash');
        }

        if ($request->action == 'restore') {
            DB::table('students')
                ->whereIn('id', $ids)
                ->update([
                    'deleted_at' => null,
                    'updated_at' => now(),
                ]);

            return back()->with('success', 'Selected students restored');
        }

        if ($request->action == 'export') {
            $students = DB::table('students')
                ->whereIn('id', $ids)
                ->get();

            $fileName = 'students_bulk.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];

            $callback = function () use ($students) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['ID', 'Name', 'Email', 'Age', 'Created At']);
                foreach ($students as $student) {
                    fputcsv($file, [
                        $student->id,
                        $student->name,
                        $student->email,
                        $student->age,
                        $student->created_at,
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
    }

    public function export(Request $request)
    {
        $students = DB::table('students')
            ->when(!$request->trashed, function ($query) {
                $query->whereNull('deleted_at');
            })
            ->when($request->trashed, function ($query) {
                $query->whereNotNull('deleted_at');
            })

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
            ->when($request->sort == 'name_asc', function ($query) {
                $query->orderBy('name', 'asc');
            })
            ->when($request->sort == 'name_desc', function ($query) {
                $query->orderBy('name', 'desc');
            })
            ->when($request->sort == 'date_asc', function ($query) {
                $query->orderBy('created_at', 'asc');
            })
            ->when($request->sort == 'date_desc', function ($query) {
                $query->orderBy('created_at', 'desc');
            })

            ->get();

        if ($request->format == 'json') {
            return response()->json($students);
        }

        $fileName = 'students.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Age', 'Created At']);
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->id,
                    $student->name,
                    $student->email,
                    $student->age,
                    $student->created_at,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = fopen($request->file('csv_file')->getRealPath(), 'r');
        $header = fgetcsv($file);
        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < 3) {
                $skipped++;
                continue;
            }

            $name = $row[0];
            $email = $row[1];
            $age = $row[2];

            if (empty($name) || empty($email) || empty($age)) {
                $skipped++;
                continue;
            }

            $emailExists = DB::table('students')
                ->where('email', $email)
                ->exists();

            if ($emailExists) {
                $skipped++;
                continue;
            }

            DB::table('students')->insert([
                'name' => $name,
                'email' => $email,
                'age' => $age,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $imported++;
        }

        fclose($file);

        return redirect('/students')
            ->with('success', "Import completed! Imported: {$imported}, Skipped: {$skipped}");
    }

    public function importForm()
    {
        return view('students.import');
    }

    public function checkEmail(Request $request)
    {
        $exists = DB::table('students')
            ->where('email', $request->email)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function checkEmailEdit(Request $request)
    {
        $exists = DB::table('students')
            ->where('email', $request->email)
            ->where('id', '!=', $request->id)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function printView(Request $request)
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
            ->orderBy('id', 'asc')
            ->paginate(100)
            ->withQueryString();

        return view('students.print', compact('students'));
    }
}
