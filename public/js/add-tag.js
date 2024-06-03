const tags = document.getElementById('tags');
        const input = document.getElementById('input-tag');
        const inputHiddenMeta = document.getElementById('meta_keywords');

        input.addEventListener('keydown', function(event) {
            // Check if the key pressed is 'Enter'
            if (event.key === 'Enter') {
                // Prevent the default action of the keypress event (submitting the form)
                event.preventDefault();

                // Create a new list item element for the tag
                const tag = document.createElement('li');

                // Get the trimmed value of the input element
                const tagContent = input.value.trim();

                // If the trimmed value is not an empty string
                if (tagContent !== '') {
                    // Set the text content of the tag to the trimmed value
                    tag.innerText = tagContent;

                    // Add a delete button to the tag
                    const deleteButton = document.createElement('button');
                    deleteButton.classList.add('delete-button');
                    deleteButton.innerText = 'X';
                    tag.appendChild(deleteButton);

                    // Append the tag to the tags list
                    tags.appendChild(tag);

                    // Append the tag to the hidden input, separated by commas
                    let currentKeywords = inputHiddenMeta.value ? inputHiddenMeta.value.split(',').map(keyword =>
                        keyword.trim()) : [];
                    currentKeywords.push(tagContent);
                    inputHiddenMeta.value = currentKeywords.join(', ');

                    // Clear the input element's value
                    input.value = '';
                }
            }
        });

        // Add an event listener for click on the tags list
        tags.addEventListener('click', function(event) {
            // If the clicked element has the class 'delete-button'
            if (event.target.classList.contains('delete-button')) {
                // Get the parent element (the tag)
                const tag = event.target.parentNode;

                // Get the text content of the tag
                const tagContent = tag.firstChild.textContent.trim();

                // Remove the tag content from the hidden input
                let currentKeywords = inputHiddenMeta.value.split(',').map(keyword => keyword.trim());
                currentKeywords = currentKeywords.filter(keyword => keyword !== tagContent);
                inputHiddenMeta.value = currentKeywords.join(', ');

                // Remove the parent element (the tag)
                tag.remove();
            }
        });