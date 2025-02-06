@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Blog Posts</h1>

    <!-- Create Post Button -->
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createPostModal">
        Create Post
    </button>

    <!-- Blog Posts Table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
                <tr>
                    <td>
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="img-thumbnail"
                                width="100">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>{{ $post->title }}</td>
                    <td>{{ Str::limit($post->description, 100) }}</td>
                    <td>
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-info btn-sm">View</a>
                        <a href="#" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editPostModal"
                            data-id="{{ $post->id }}" data-title="{{ $post->title }}"
                            data-description="{{ $post->description }}" data-image="{{ asset('storage/' . $post->image) }}">
                            Edit
                        </a>

                        <!-- Delete Form with SweetAlert -->
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $post->id }}">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No data yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal for Creating Post -->
@include('posts.create')
<!-- Modal for update Post -->
@include('posts.edit')

<!-- SweetAlert2 for Success Messages -->
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    </script>
@endif

<!-- SweetAlert2 for Errors -->
@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}",
        });
    </script>
@endif

<!-- SweetAlert2 for Delete Confirmation -->
<script>
    // Delete Confirmation
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form via fetch
                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => { throw err; });
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    window.location.href = '/'; // Redirect to home page
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: data.message || 'Something went wrong.',
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: error.message || 'Something went wrong.',
                            });
                        });
                }
            });
        });
    });
                    // Update Post
    document.querySelectorAll('.btn-warning').forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            const description = this.getAttribute('data-description');
            const image = this.getAttribute('data-image');

            // Set the form action dynamically
            const form = document.getElementById('editPostForm');
            form.action = `/posts/${postId}`; // Replace with your update route

            // Populate form fields
            document.getElementById('editTitle').value = title;
            document.getElementById('editDescription').value = description;

            // Handle form submission
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            $('#editPostModal').modal('hide'); // Close modal
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = '/'; // Redirect to home page
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Something went wrong.',
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: error.message || 'Something went wrong.',
                        });
                    });
            });
        });
    });

                            // Add Post
    document.addEventListener("DOMContentLoaded", function () {
        const createPostForm = document.getElementById('createPostForm');
        if (createPostForm) {
            createPostForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent default form submission

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json', // Ensure JSON response
                    },
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => { throw err; });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Close the modal
                            $('#createPostModal').modal('hide');
                            // Show success alert
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.reload(); // Reload the page to show the new post
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'Something went wrong.',
                            });
                        }
                    })
                    .catch(error => {
                        // Handle validation errors
                        if (error.errors) {
                            let errorMessages = '';
                            for (const field in error.errors) {
                                errorMessages += error.errors[field].join('<br>') + '<br>';
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error!',
                                html: errorMessages,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: error.message || 'Something went wrong.',
                            });
                        }
                    });
            });
        }
    });
</script>

@endsection