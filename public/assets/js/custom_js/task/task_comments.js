/**
 * Task Comments JavaScript
 * Handles comment and reply functionality for tasks
 */

$(document).ready(function() {
    // Initialize Quill editors
    var fullToolbar = [
        [{ font: [] }, { size: [] }],
        ["bold", "italic", "underline", "strike"],
        [{ color: [] }, { background: [] }],
        ["link"],
        [{ header: "1" }, { header: "2" }, "blockquote"],
        [{ list: "ordered" }, { list: "bullet" }],
    ];

    // Store reply editors
    var replyEditors = {};

    // Initialize main comment editor if it exists
    if (document.getElementById('taskCommentEditor')) {
        var taskCommentEditor = new Quill("#taskCommentEditor", {
            bounds: "#taskCommentEditor",
            placeholder: "Write your comment...",
            modules: {
                formula: true,
                toolbar: fullToolbar,
            },
            theme: "snow",
        });

        // Set the editor content to the old comment if validation fails
        var oldComment = $('#commentInput').val();
        if (oldComment) {
            taskCommentEditor.root.innerHTML = oldComment;
        }

        // Handle main comment form submission
        $('#taskCommentForm').on('submit', function() {
            $('#commentInput').val(taskCommentEditor.root.innerHTML);
        });
    }

    // Handle reply button clicks
    $(document).on('click', '.reply-btn', function() {
        var commentId = $(this).data('comment-id');
        var replyForm = $('#replyForm-' + commentId);

        // Toggle reply form visibility
        if (replyForm.is(':visible')) {
            replyForm.hide();
        } else {
            // Hide all other reply forms
            $('.reply-form-container').hide();
            replyForm.show();

            // Initialize Quill editor for this reply if not already initialized
            if (!replyEditors[commentId]) {
                replyEditors[commentId] = new Quill("#replyEditor-" + commentId, {
                    bounds: "#replyEditor-" + commentId,
                    placeholder: "Write your reply...",
                    modules: {
                        formula: true,
                        toolbar: fullToolbar,
                    },
                    theme: "snow",
                });
            }
        }
    });

    // Handle cancel reply buttons
    $(document).on('click', '.cancel-reply', function() {
        var commentId = $(this).data('comment-id');
        $('#replyForm-' + commentId).hide();

        // Clear the editor content
        if (replyEditors[commentId]) {
            replyEditors[commentId].setContents([]);
        }
    });

    // Handle reply form submissions
    $(document).on('submit', '.reply-form', function() {
        var form = $(this);
        var commentId = form.find('input[name="parent_comment_id"]').val();
        var replyInput = form.find('.reply-input');

        if (replyEditors[commentId]) {
            replyInput.val(replyEditors[commentId].root.innerHTML);
        }
    });

    // Lightbox configuration for comment images
    if (typeof lightbox !== 'undefined') {
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': "Image %1 of %2"
        });
    }
});
