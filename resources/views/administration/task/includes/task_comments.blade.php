<div class="card mb-4">
    <div class="card-header header-elements">
        <h5 class="mb-0">Task Comments</h5>

        <div class="card-header-elements ms-auto">
            {{-- @if (($task->users->contains(auth()->user()->id) && !is_null($hasUnderstood)) || $task->creator_id == auth()->user()->id)
                <button type="button" class="btn btn-primary btn-xs" title="Create Comment" data-bs-toggle="collapse" data-bs-target="#taskComment" aria-expanded="false" aria-controls="taskComment">
                    <span class="tf-icon ti ti-message-circle ti-xs me-1"></span>
                    Comment
                </button>
            @endif --}}
        </div>
    </div>
    <!-- Account -->
    <div class="card-body">
        @if (($task->users->contains(auth()->user()->id) && !is_null($hasUnderstood)) || $task->creator_id == auth()->user()->id)
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('administration.task.comment.store', ['task' => $task]) }}" method="post" enctype="multipart/form-data" autocomplete="off" id="taskCommentForm">
                        @csrf
                        <div class="collapse show" id="taskComment">
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <div name="comment" id="taskCommentEditor">{!! old('comment') !!}</div>
                                    <textarea class="d-none" name="comment" id="commentInput">{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <b class="text-danger">{{ $message }}</b>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-1">
                                    <div id="filePreviewContainer" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;"></div>
                                    
                                    <div id="fileDropZone" class="file-drop-zone" style="border: 2px dashed #ccc; padding: 20px; text-align: center; margin-top: 10px; cursor: pointer; transition: all 0.3s ease;">
                                        <div style="margin-bottom: 10px;">
                                            <i class="ti ti-cloud-upload" style="font-size: 2rem; color: #7367f0;"></i>
                                        </div>
                                        <div style="font-weight: 500; margin-bottom: 5px;">Drag & Drop or Paste Files Here</div>
                                        <small style="color: #999;">Or click to browse</small>
                                        <br>
                                        <span id="fileStatus" style="font-weight: bold; color: green; display: block; margin-top: 10px;"></span>
                                    </div>

                                    <input type="hidden" id="pastedImageFile" name="pasted_image_data_url">
                                    
                                    <input type="file" id="comment-files" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Task Comment Files') }}" class="form-control @error('files[]') is-invalid @enderror" style="display: none;"/>
                                    @error('files[]')
                                        <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block mt-2 mb-3">
                                        <i class="ti ti-check"></i>
                                        Submit Comment
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12 comments">
                @php
                    $senderColor = 'background-color: #f0676714 !important; border: 1px solid #f067675c !important;';
                    $receiverColor = 'background-color: #7367f014 !important; border: 1px solid #7367f05c !important;';
                @endphp

                @foreach ($task->all_comments()->with(['commenter.employee', 'commenter.media', 'files', 'parent_comment.commenter.employee', 'parent_comment.commenter.media'])->orderByDesc('created_at')->get() as $comment)
                    <div class="comment-container mb-4 p-3 rounded chat-message {{ $comment->parent_comment_id ? 'has-parent' : '' }}" id="comment-{{ $comment->id }}" style="{{ $comment->commenter->id == auth()->user()->id ? $senderColor : $receiverColor }}">
                        <!-- Parent Comment Preview (if this is a reply) -->
                        @if ($comment->parent_comment_id)
                            @php
                                $parentComment = $comment->parent_comment;
                                $isReplyToMainComment = $parentComment->parent_comment_id === null;
                            @endphp
                            <div class="parent-comment-preview mb-3 p-2 rounded" style="background-color: rgba(0,0,0,0.05); border-left: 2px solid #dee2e6; cursor: pointer;" onclick="scrollToComment({{ $parentComment->id }})">
                                <div class="d-flex align-items-center mb-1">
                                    {!! show_user_name_and_avatar($parentComment->commenter, name: false, avatar: false) !!}
                                    <small class="text-muted ms-2">
                                        <i class="ti ti-corner-down-left me-1"></i>
                                        Replying to {{ $isReplyToMainComment ? 'this comment' : 'this reply' }}
                                    </small>
                                </div>
                                <div class="text-muted small" style="line-height: 1.2;">
                                    @php
                                        $truncatedComment = strip_tags($parentComment->comment);
                                        $truncatedComment = show_content($truncatedComment, 80);
                                    @endphp
                                    {{ $truncatedComment }}
                                </div>
                            </div>
                        @endif

                        <!-- Comment Content -->
                        <div class="main-comment">
                            <div class="d-flex justify-content-between align-items-center user-name mb-2">
                                {!! show_user_name_and_avatar($comment->commenter, name: false) !!}
                                <small class="date-time text-muted">{{ date_time_ago($comment->created_at) }}</small>
                            </div>

                            <div class="comment-content mb-2">
                                {!! $comment->comment !!}
                            </div>

                            @if ($comment->files->count() > 0)
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @foreach ($comment->files as $commentFile)
                                        @if (in_array($commentFile->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']))
                                            <div class="comment-image-container" title="Click to view {{ $commentFile->original_name }}">
                                                <a href="{{ file_media_download($commentFile) }}" data-lightbox="comment-images-{{ $comment->id }}" data-title="{{ $commentFile->original_name }}">
                                                    <img src="{{ file_media_download($commentFile) }}" alt="{{ $commentFile->original_name }}" class="img-fluid img-thumbnail" style="width: 150px; height: 100px; object-fit: cover;">
                                                </a>
                                            </div>
                                        @else
                                            <div class="file-thumbnail-container" title="Click to Download {{ $commentFile->original_name }}">
                                                <a href="{{ file_media_download($commentFile) }}" target="_blank" class="text-decoration-none">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="ti ti-file-download fs-2 mb-2 text-primary"></i>
                                                        <span class="file-name text-center small fw-medium">
                                                            {{ show_content($commentFile->original_name, 15) }}
                                                        </span>
                                                        <small class="text-muted">{{ strtoupper(pathinfo($commentFile->original_name, PATHINFO_EXTENSION)) }}</small>
                                                    </div>
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            <!-- Reply Button -->
                            @if (($task->users->contains(auth()->user()->id) && !is_null($hasUnderstood)) || $task->creator_id == auth()->user()->id)
                                <div class="d-flex justify-content-end mb-2">
                                    <a href="javascript:void(0);" class="text-primary text-bold reply-btn" data-comment-id="{{ $comment->id }}" title="Reply">
                                        <i class="ti ti-arrow-back-up me-1"></i>
                                        Reply
                                    </a>
                                </div>
                            @endif

                            <!-- Reply Form -->
                            @if (($task->users->contains(auth()->user()->id) && !is_null($hasUnderstood)) || $task->creator_id == auth()->user()->id)
                                <div class="reply-form-container" id="replyForm-{{ $comment->id }}" style="display: none;">
                                    <form action="{{ route('administration.task.comment.store', ['task' => $task]) }}" method="post" enctype="multipart/form-data" autocomplete="off" class="reply-form">
                                        @csrf
                                        <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}">
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <div class="reply-editor" id="replyEditor-{{ $comment->id }}"></div>
                                                <textarea class="d-none reply-input" name="comment"></textarea>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <div class="reply-file-preview-container" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;"></div>
                                                
                                                <div class="reply-file-drop-zone file-drop-zone" style="border: 2px dashed #ccc; padding: 15px; text-align: center; margin-top: 10px; cursor: pointer; transition: all 0.3s ease;" data-comment-id="{{ $comment->id }}">
                                                    <div style="margin-bottom: 8px;">
                                                        <i class="ti ti-cloud-upload" style="font-size: 1.5rem; color: #7367f0;"></i>
                                                    </div>
                                                    <div style="font-weight: 500; font-size: 0.9rem; margin-bottom: 3px;">Drag & Drop or Paste Files</div>
                                                    <small style="color: #999;">Or click to browse</small>
                                                    <br>
                                                    <span class="reply-file-status" style="font-weight: bold; color: green; display: block; margin-top: 8px; font-size: 0.85rem;"></span>
                                                </div>
                                                <input type="file" name="files[]" class="reply-file-input" style="display: none;" multiple/>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary btn-sm me-2">
                                                    <i class="ti ti-check"></i>
                                                    Submit Reply
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm cancel-reply" data-comment-id="{{ $comment->id }}">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB limit
    const initializedDropZones = new Set(); // Track initialized drop zones
    const initializedForms = new Set(); // Track initialized forms

    // Helper function to generate image previews
    function generateImagePreviews(previewContainer, files, fileInput) {
        previewContainer.innerHTML = '';
        
        if (!files || files.length === 0) {
            return;
        }

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const fileIndex = i;
            
            // Check if file is an image
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.style.cssText = 'position: relative; display: inline-block; margin: 5px;';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.cssText = 'width: 100px; height: 100px; object-fit: cover; border-radius: 6px; border: 2px solid #ddd; box-shadow: 0 2px 4px rgba(0,0,0,0.1);';
                    img.title = file.name;
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.innerHTML = '<i class="ti ti-x" style="font-size: 1rem;"></i>';
                    removeBtn.style.cssText = 'position: absolute; top: -10px; right: -10px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 28px; height: 28px; padding: 0; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.2s ease;';
                    removeBtn.title = 'Remove image';
                    
                    removeBtn.addEventListener('mouseover', () => {
                        removeBtn.style.background = '#c82333';
                        removeBtn.style.transform = 'scale(1.1)';
                    });
                    
                    removeBtn.addEventListener('mouseout', () => {
                        removeBtn.style.background = '#dc3545';
                        removeBtn.style.transform = 'scale(1)';
                    });
                    
                    removeBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Remove file from input
                        const dataTransfer = new DataTransfer();
                        for (let j = 0; j < fileInput.files.length; j++) {
                            if (j !== fileIndex) {
                                dataTransfer.items.add(fileInput.files[j]);
                            }
                        }
                        fileInput.files = dataTransfer.files;
                        
                        // Regenerate previews
                        const form = fileInput.closest('form');
                        const statusElement = form.querySelector('.reply-file-status') || form.querySelector('#fileStatus');
                        updateFileStatus(statusElement, fileInput.files);
                        generateImagePreviews(previewContainer, fileInput.files, fileInput);
                    });
                    
                    previewDiv.appendChild(img);
                    previewDiv.appendChild(removeBtn);
                    previewContainer.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            }
        }
    }

    // Helper function to update file status display
    function updateFileStatus(statusElement, files) {
        if (!files || files.length === 0) {
            statusElement.innerHTML = '';
            return;
        }

        let html = `<i class="ti ti-check-circle" style="color: green;"></i> ${files.length} file(s) selected:<br>`;
        for (let i = 0; i < files.length; i++) {
            const size = (files[i].size / 1024 / 1024).toFixed(2);
            html += `<small>${files[i].name} (${size} MB)</small><br>`;
        }
        statusElement.innerHTML = html;
    }

    // Helper function to validate file
    function validateFile(file) {
        if (file.size > MAX_FILE_SIZE) {
            alert(`File "${file.name}" is too large (max 5MB).`);
            return false;
        }
        return true;
    }

    // Helper function to add files to file input
    function addFilesToInput(fileInput, newFiles) {
        const dataTransfer = new DataTransfer();
        
        // Add new files only (don't accumulate existing files)
        for (let i = 0; i < newFiles.length; i++) {
            if (validateFile(newFiles[i])) {
                dataTransfer.items.add(newFiles[i]);
            }
        }
        
        fileInput.files = dataTransfer.files;
    }

    // Setup file drop zone
    function setupFileDropZone(dropZone, fileInput, statusElement, previewContainer) {
        // Check if already initialized
        if (initializedDropZones.has(dropZone)) {
            return;
        }
        initializedDropZones.add(dropZone);

        // Click to browse
        const clickHandler = () => {
            fileInput.click();
        };
        dropZone.addEventListener('click', clickHandler);

        // File input change
        const changeHandler = () => {
            updateFileStatus(statusElement, fileInput.files);
            if (previewContainer) {
                generateImagePreviews(previewContainer, fileInput.files, fileInput);
            }
        };
        fileInput.addEventListener('change', changeHandler);

        // Drag over
        const dragoverHandler = (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.backgroundColor = '#e8e4f3';
            dropZone.style.borderColor = '#7367f0';
        };
        dropZone.addEventListener('dragover', dragoverHandler);

        // Drag leave
        const dragleaveHandler = (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.backgroundColor = 'transparent';
            dropZone.style.borderColor = '#ccc';
        };
        dropZone.addEventListener('dragleave', dragleaveHandler);

        // Drop
        const dropHandler = (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.style.backgroundColor = 'transparent';
            dropZone.style.borderColor = '#ccc';
            
            if (e.dataTransfer.files.length) {
                addFilesToInput(fileInput, e.dataTransfer.files);
                updateFileStatus(statusElement, fileInput.files);
                if (previewContainer) {
                    generateImagePreviews(previewContainer, fileInput.files, fileInput);
                }
            }
        };
        dropZone.addEventListener('drop', dropHandler);
    }

    // Setup paste functionality for a specific form
    function setupPasteForForm(form) {
        // Check if already initialized
        if (initializedForms.has(form)) {
            return;
        }
        initializedForms.add(form);

        const pasteHandler = (e) => {
            const items = (e.clipboardData || e.originalEvent.clipboardData).items;
            const files = [];
            
            for (let i = 0; i < items.length; i++) {
                if (items[i].kind === 'file') {
                    const file = items[i].getAsFile();
                    if (file) {
                        files.push(file);
                    }
                }
            }
            
            if (files.length > 0) {
                e.preventDefault();
                const fileInput = form.querySelector('input[type="file"]');
                const statusElement = form.querySelector('.reply-file-status') || form.querySelector('#fileStatus');
                const previewContainer = form.querySelector('.reply-file-preview-container') || form.querySelector('#filePreviewContainer');
                if (fileInput) {
                    addFilesToInput(fileInput, files);
                    updateFileStatus(statusElement, fileInput.files);
                    if (previewContainer) {
                        generateImagePreviews(previewContainer, fileInput.files, fileInput);
                    }
                }
            }
        };
        form.addEventListener('paste', pasteHandler);
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Setup main comment form
        const mainDropZone = document.getElementById('fileDropZone');
        const mainFileInput = document.getElementById('comment-files');
        const mainStatusElement = document.getElementById('fileStatus');
        const mainPreviewContainer = document.getElementById('filePreviewContainer');
        
        if (mainDropZone && mainFileInput) {
            setupFileDropZone(mainDropZone, mainFileInput, mainStatusElement, mainPreviewContainer);
            setupPasteForForm(document.getElementById('taskCommentForm'));
        }

        // Setup reply forms (for dynamically added forms)
        function setupReplyForms() {
            document.querySelectorAll('.reply-file-drop-zone').forEach(dropZone => {
                const form = dropZone.closest('.reply-form');
                const fileInput = form.querySelector('.reply-file-input');
                const statusElement = dropZone.querySelector('.reply-file-status');
                const previewContainer = form.querySelector('.reply-file-preview-container');
                
                setupFileDropZone(dropZone, fileInput, statusElement, previewContainer);
                setupPasteForForm(form);
            });
        }

        setupReplyForms();

        // Re-setup reply forms when reply button is clicked
        document.addEventListener('click', (e) => {
            if (e.target.closest('.reply-btn')) {
                setTimeout(setupReplyForms, 100);
            }
        });
    });

    // Function to scroll to a specific comment
    function scrollToComment(commentId) {
        const commentElement = document.getElementById('comment-' + commentId);
        if (commentElement) {
            // Add highlight effect
            commentElement.style.transition = 'all 0.3s ease';
            commentElement.style.boxShadow = '0 0 15px rgba(115, 103, 240, 0.5)';

            // Scroll to the comment
            commentElement.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            // Remove highlight after 2 seconds
            setTimeout(() => {
                commentElement.style.boxShadow = '';
            }, 2000);
        }
    }
</script>


