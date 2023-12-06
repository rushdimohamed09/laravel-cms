document.getElementById('html-preview').addEventListener('input', function() {
    // Synchronize changes to the edit mode textarea.
    document.getElementById('html-code').value = this.innerHTML;
});

// When switching between modes, populate the preview mode.
document.getElementById('preview-mode-btn').addEventListener('change', function() {
    if (this.checked) {
        // Switch to preview mode
        switchToPreviewMode();

        // Populate the preview mode with the HTML code from the edit mode textarea.
        document.getElementById('html-preview').innerHTML = document.getElementById('html-code').value;
    } else {
        // Switch back to edit mode
        switchToEditMode();
    }
});


document.getElementById('preview-mode-btn').addEventListener('change', function() {
    if (this.checked) {
        console.log('hello');
        switchToPreviewMode();
    } else {
        console.log('mello');
        switchToEditMode();
    }
});

function switchToEditMode() {
    document.getElementById('edit-mode').style.display = 'block';
    document.getElementById('preview-mode').style.display = 'none';
}

function switchToPreviewMode() {
    const htmlCode = document.getElementById('html-code').value;
    document.getElementById('html-preview').innerHTML = htmlCode;

    document.getElementById('edit-mode').style.display = 'none';
    document.getElementById('preview-mode').style.display = 'block';
}
