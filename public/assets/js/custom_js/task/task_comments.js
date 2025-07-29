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
        $('#taskCommentForm').on('submit', function(e) {
            var content = taskCommentEditor.root.innerHTML;
            var textContent = taskCommentEditor.getText().trim();

            // Check if content is empty (only contains empty tags)
            if (!textContent) {
                e.preventDefault();
                alert('Please enter a comment before submitting.');
                return false;
            }

            $('#commentInput').val(content);

            // Add loading state
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="ti ti-loader"></i> Submitting...');
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
    $(document).on('submit', '.reply-form', function(e) {
        var form = $(this);
        var commentId = form.find('input[name="parent_comment_id"]').val();
        var replyInput = form.find('.reply-input');

        if (replyEditors[commentId]) {
            var content = replyEditors[commentId].root.innerHTML;

            // Check if content is empty (only contains empty tags)
            var textContent = replyEditors[commentId].getText().trim();
            if (!textContent) {
                e.preventDefault();
                alert('Please enter a reply before submitting.');
                return false;
            }

            replyInput.val(content);

            // Add loading state
            form.addClass('loading');
            form.find('button[type="submit"]').prop('disabled', true).html('<i class="ti ti-loader"></i> Submitting...');
        }
    });

    // Function to scroll to a specific comment (also available globally)
    window.scrollToComment = function(commentId) {
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
    };

    // Lightbox configuration for comment images
    if (typeof lightbox !== 'undefined') {
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': "Image %1 of %2"
        });
    }
});
