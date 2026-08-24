@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header bg-primary text-white">
        Add Student
    </div>

    <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="/students/store" id="createForm">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
                <div id="emailError" class="text-danger mt-1" style="display: none;">Email already exists!</div>
            </div>

            <div class="mb-3">
                <label>Age</label>
                <input type="number" name="age" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Save</button>
            <a href="/students" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $('#email').on('input', function() {
        var email = $(this).val();
        if (email.length > 0) {
            $.ajax({
                url: '/students/check-email',
                type: 'GET',
                data: { email: email },
                success: function(response) {
                    if (response.exists) {
                        $('#emailError').show();
                        $('#createForm').attr('onsubmit', 'return false;');
                    } else {
                        $('#emailError').hide();
                        $('#createForm').removeAttr('onsubmit');
                    }
                }
            });
        }
    });
</script>

@endsection
