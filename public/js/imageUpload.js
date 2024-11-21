document.addEventListener('DOMContentLoaded', () => {
    const fileInput = document.querySelector('input[name="add_event[eventImages][]"]');
    const previewContainer = document.getElementById('image-preview-container');

    fileInput.addEventListener('change', (event) => {
        previewContainer.innerHTML = ''; // Réinitialiser les prévisualisations
        const files = event.target.files;

        Array.from(files).forEach((file) => {
            const reader = new FileReader();

            reader.onload = (e) => {
                const imageBase64 = e.target.result;
                const imgElement = document.createElement('img');
                imgElement.src = imageBase64;
                imgElement.style.width = '100px'; // Ajustez les dimensions
                imgElement.style.margin = '5px';

                previewContainer.appendChild(imgElement);

                // Ajoutez l'image en base64 à un champ caché dans le formulaire
                const base64Input = document.createElement('input');
                base64Input.type = 'hidden';
                base64Input.name = 'base64Images[]'; // Assurez-vous d'utiliser un tableau pour gérer plusieurs images
                base64Input.value = imageBase64;
                document.querySelector('form').appendChild(base64Input);
            };

            reader.readAsDataURL(file); // Lecture du fichier en base64
        });
    });
});