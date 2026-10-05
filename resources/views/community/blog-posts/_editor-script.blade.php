    <script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.quill-editor').forEach(function (editorElement) {

        const editorId = editorElement.id;

        const locale = editorId.replace('editor-', '');

        const hiddenInput = document.getElementById(
            locale + '-content'
        );

        const initialContent =
            editorElement.dataset.content || '';

        const quill = new Quill(editorElement, {
            theme: 'snow',

            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ align: [] }],
                    ['blockquote'],
                    ['link'],
                    ['clean']
                ]
            },

            placeholder: 'Write your content here...'
        });


        /*
         * Load existing content when editing.
         */
        if (initialContent) {
            quill.root.innerHTML = initialContent;
        }


        /*
         * Copy HTML content into hidden input
         * before form submission.
         */
        const form = editorElement.closest('form');

        form.addEventListener('submit', function () {
            hiddenInput.value = quill.root.innerHTML;
        });

    });

});
</script>