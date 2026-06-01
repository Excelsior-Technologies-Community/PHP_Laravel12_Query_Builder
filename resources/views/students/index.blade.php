@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Student Management</h2>

    <div>

        <a href="{{ url('/students/export?search='.request('search').'&sort='.request('sort')) }}"
           class="btn btn-success me-2">
            Export CSV
        </a>

        <a href="/students/create"
           class="btn btn-primary">
            + Add Student
        </a>

    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Statistics Dashboard -->

<div class="row mb-4">

    <div class="col-md-3">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <h6>Total Students</h6>
                <h2>{{ $totalStudents }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <h6>Average Age</h6>
                <h2>{{ round($averageAge) }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <h6>Youngest</h6>
                <h2>{{ $youngest }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow border-0">
            <div class="card-body text-center">
                <h6>Oldest</h6>
                <h2>{{ $oldest }}</h2>
            </div>
        </div>
    </div>

</div>

<!-- Search + Sort -->

<form method="GET" action="/students" class="mb-4">
    <div class="row">

        <div class="col-md-8">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by name or email..."
                value="{{ request('search') }}">
        </div>

        <div class="col-md-2">
            <select
                name="sort"
                class="form-select"
                onchange="this.form.submit()">

                <option value="">Sort Age</option>

                <option value="age_asc"
                    {{ request('sort') == 'age_asc' ? 'selected' : '' }}>
                    Low → High
                </option>

                <option value="age_desc"
                    {{ request('sort') == 'age_desc' ? 'selected' : '' }}>
                    High → Low
                </option>

            </select>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">
                Search
            </button>
        </div>

    </div>
</form>

<!-- Student Table -->

<div class="card shadow border-0">
    <div class="card-body">

        <table class="table table-bordered table-hover align-middle">

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

                @forelse($students as $student)

                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->age }}</td>

                    <td>

                        <a href="/students/edit/{{ $student->id }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <a href="/students/delete/{{ $student->id }}"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure?')">
                            Delete
                        </a>

                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="5" class="text-center text-danger">
                        No students found.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>
</div>

@endsection