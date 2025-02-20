document.addEventListener("DOMContentLoaded", (event) => {
    document.querySelectorAll(".drag-drop-input").forEach(function (fileInput) {
        const dropZone = fileInput.parentElement.querySelector('.drag-drop-input__drop-zone');
        
        dropZone.addEventListener('click', () => fileInput.click());

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, event => {
                event.preventDefault();
                event.stopPropagation();
            }, false);
        });
    
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('drag-drop-input__drop-zone--active'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('drag-drop-input__drop-zone--active'), false);
        });
    
        dropZone.addEventListener('drop', event => {
            const files = event.dataTransfer.files;
            const dataTransfer = new DataTransfer();
            
            for (let i = 0; i < files.length; i++) {
                dataTransfer.items.add(files[i]);
            }
            
            fileInput.files = dataTransfer.files;
            
            const changeEvent = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(changeEvent);
            dropZone.innerHTML = 'File uploaded !';
        });
    });
});
