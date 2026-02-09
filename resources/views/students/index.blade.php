@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h3>Students List</h3>
    <a href="/students/create" class="btn btn-primary">+ Add Student</a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Age</th>
            <th width="180">Action</th>
        </tr>
    </thead>

    <tbody>
        @foreach($students as $student)
        <tr>
            <td>{{ $student->id }}</td>
            <td>{{ $student->name }}</td>
            <td>{{ $student->email }}</td>
            <td>{{ $student->age }}</td>
            <td>
                <a href="/students/edit/{{ $student->id }}" class="btn btn-sm btn-warning">Edit</a>
                <a href="/students/delete/{{ $student->id }}"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Are you sure?')">
                   Delete
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
