@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <div class="card shadow border-0">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Student Details</h4>
        </div>

        <div class="card-body">

            @if($student)

                <h3 class="mb-3">{{ $student->name }}</h3>

                <p><strong>ID:</strong> {{ $student->id }}</p>

                <p><strong>Email:</strong> {{ $student->email }}</p>

                <p><strong>Age:</strong> {{ $student->age }}</p>

                <a href="/students" class="btn btn-secondary mt-3">
                    ← Back
                </a>

            @else

                <div class="alert alert-danger">
                    Student not found!
                </div>

            @endif

        </div>

    </div>

</div>

@endsection