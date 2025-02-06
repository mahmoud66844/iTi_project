<!-- Modal for Editing Post -->
<div class="modal fade" id="editPostModal" tabindex="-1" role="dialog" aria-labelledby="editPostModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Edit Post Form -->
                <form id="editPostForm" action="{{ route('posts.update', ':id') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="editTitle">Title</label>
                        <input type="text" class="form-control" name="title" id="editTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="editDescription">Description</label>
                        <textarea class="form-control" name="description" id="editDescription" rows="3"
                            required></textarea>
                    </div>
                    <!-- <img id="imagePreview" src="" alt="Image Preview" style="max-width: 100%; margin-top: 10px;"> -->

                    <div class="form-group">
                        <label for="editImage">Image</label>
                        <input type="file" class="form-control-file" name="image" id="editImage">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Post</button>
                </form>
            </div>
        </div>
    </div>
</div>