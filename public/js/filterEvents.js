document.querySelector('.filter').addEventListener('input', function (event) {
    event.preventDefault();

    // Récupération des données du formulaire
    const formData = new FormData(event.target.closest('form'));

    // Envoi de la requête AJAX
    fetch('/filtered_events', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            const eventList = document.querySelector('.event-list');
            eventList.innerHTML = ''; // Vider la liste existante

            if (data.length === 0) {
                eventList.innerHTML = '<p class="no-events">Aucun événement trouvé.</p>';
                return;
            }

            // Ajouter les événements filtrés
            data.forEach(event => {
                const eventItem = `
                    <li class="event-item d-flex align-items-center">
                        <div class="event-image-container">
                            <img src="/images/events/${event.image || 'default.jpg'}" alt="${event.title}" class="event-image">
                        </div>
                        <div class="event-content ms-3 flex-grow-1">
                            <h3 class="event-title">${event.title}</h3>
                            <p class="event-participants">Joueurs max ${event.maxUser}</p>
                            <p class="event-description">${event.description}</p>
                            <p class="event-date">${event.startDate}</p>
                        </div>
                        <div class="event-buttons">
                            <a href="${event.detailLink}" class="btn btn-primary btn-sm">Voir Détails</a>
                        </div>
                    </li>`;
                eventList.insertAdjacentHTML('beforeend', eventItem);
            });
        })
        .catch(error => console.error('Erreur lors de la récupération des événements :', error));
});
