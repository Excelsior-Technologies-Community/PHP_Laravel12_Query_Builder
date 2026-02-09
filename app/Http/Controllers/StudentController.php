<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // LIST PAGE
    public function index()
    {
        $students = DB::table('students')->get();
        return view('students.index', compact('students'));
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
            'name'  => $request->name,
            'email' => $request->email,
            'age'   => $request->age,
        ]);

        return redirect('/students')->with('success', 'Student added successfully');
    }

    // EDIT FORM
    public function edit($id)
    {
        $student = DB::table('students')->where('id', $id)->first();
        return view('students.edit', compact('student'));
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        DB::table('students')
            ->where('id', $id)
            ->update([
                'name'  => $request->name,
                'email' => $request->email,
                'age'   => $request->age,
            ]);

        return redirect('/students')->with('success', 'Student updated successfully');
    }

    // DELETE
    public function delete($id)
    {
        DB::table('students')->where('id', $id)->delete();
        return redirect('/students')->with('success', 'Student deleted successfully');
    }
}
