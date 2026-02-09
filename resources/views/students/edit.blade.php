@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header bg-warning">
        Edit Student
    </div>

    <div class="card-body">
        <form method="POST" action="/students/update/{{ $student->id }}">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" value="{{ $student->name }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ $student->email }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Age</label>
                <input type="number" name="age" value="{{ $student->age }}" class="form-control" required>
            </div>

            <button class="btn btn-primary">Update</button>
            <a href="/students" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

@endsection
