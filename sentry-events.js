// sentry-events.js

document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('sentry-events-container');
    const dsn = container.dataset.dsn;
    const projectID = container.dataset.projectId;
    const organizationID = container.dataset.organizationId;
    const authToken = container.dataset.authToken;

    if (!projectID || !authToken || !organizationID) {
        container.innerHTML = '<p>Project ID, Organization ID, and Auth Token are needed to view events.</p>';
        return;
    }

    const url = `https://sentry.io/api/0/projects/${organizationID}/${projectID}/events/`;

    fetch(url, {
        headers: {
            'Authorization': `Bearer ${authToken}`
        }
    })
    .then(response => response.json())
    .then(data => {
        let html = '<h2>Sentry Events</h2><ul>';
        data.forEach(event => {
            html += `<li>${event.message}</li>`;
        });
        html += '</ul>';
        container.innerHTML = html;
    })
    .catch(error => {
        console.error('Error fetching Sentry events:', error);
        container.innerHTML = '<p>Error fetching Sentry events. Check console for details.</p>';
    });
});
