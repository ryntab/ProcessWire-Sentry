// sentry-events.js
document.addEventListener("DOMContentLoaded", function() {
       // Injecting CSS styles
       const styles = `

   `;

   const styleSheet = document.createElement("style");
   styleSheet.type = "text/css";
   styleSheet.innerText = styles;
   document.head.appendChild(styleSheet);


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
        let html = '<h2>Sentry Events</h2>';
        data.forEach(event => {
            html += `
                <div class="CardPanel">
                    <div class="event-header">
                        <h3>Event ID: ${event.id}</h3>
                        <p><strong>Message:</strong> ${event.message}</p>
                        <p><strong>Title:</strong> ${event.title}</p>
                        <p><strong>Date Created:</strong> ${new Date(event.dateCreated).toLocaleString()}</p>
                    </div>
                    <div class="event-details">
                        <div class="event-section">
                            <h4>Browser</h4>
                            <p><strong>Name:</strong> ${event.tags.find(tag => tag.key === 'browser.name')?.value || 'N/A'}</p>
                            <p><strong>Version:</strong> ${event.tags.find(tag => tag.key === 'browser')?.value || 'N/A'}</p>
                        </div>
                        <div class="event-section">
                            <h4>Operating System</h4>
                            <p><strong>Name:</strong> ${event.tags.find(tag => tag.key === 'os.name')?.value || 'N/A'}</p>
                            <p><strong>Version:</strong> ${event.tags.find(tag => tag.key === 'os')?.value || 'N/A'}</p>
                        </div>
                        <div class="event-section">
                            <h4>Runtime</h4>
                            <p><strong>Name:</strong> ${event.tags.find(tag => tag.key === 'runtime.name')?.value || 'N/A'}</p>
                            <p><strong>Version:</strong> ${event.tags.find(tag => tag.key === 'runtime')?.value || 'N/A'}</p>
                        </div>
                        <div class="event-section">
                            <h4>Server</h4>
                            <p><strong>Name:</strong> ${event.tags.find(tag => tag.key === 'server_name')?.value || 'N/A'}</p>
                            <p><strong>URL:</strong> ${event.tags.find(tag => tag.key === 'url')?.value || 'N/A'}</p>
                        </div>
                    </div>
                    <hr>
                </div>
            `;
        });
        container.innerHTML = html;
    })
    .catch(error => {
        console.error('Error fetching Sentry events:', error);
        container.innerHTML = '<p>Error fetching Sentry events. Check console for details.</p>';
    });
});
