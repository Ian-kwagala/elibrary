/**
 * Initializes a real-time filter for materials on a page.
 * @param {object} options - Configuration options.
 * @param {string} options.filterFormId - The ID of the form containing filter inputs.
 * @param {string} options.gridContainerId - The ID of the container where results are displayed.
 * @param {string} options.pageType - The type of page ('book' or 'study_material').
 * @param {string} options.baseUrl - The absolute base URL of the site.
 */
function initializeFilter(options) {
    const filterForm = document.getElementById(options.filterFormId);
    const gridContainer = document.getElementById(options.gridContainerId);

    if (!filterForm || !gridContainer) {
        console.error("Filter form or grid container not found.");
        return;
    }

    // Function to fetch and render materials
    const fetchMaterials = async () => {
        // Show a loading indicator (optional)
        gridContainer.innerHTML = '<p>Loading...</p>';

        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        params.append('page_type', options.pageType);

        try {
            // FIX: Prepend the absolute base URL to the fetch path.
            const response = await fetch(`${options.baseUrl}/handlers/filter_handler.php?${params.toString()}`);
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const result = await response.json();

            if (result.success) {
                renderGrid(result.data);
            } else {
                gridContainer.innerHTML = `<p>Error: ${result.message}</p>`;
            }
        } catch (error) {
            console.error('Fetch error:', error);
            gridContainer.innerHTML = '<p>An error occurred while fetching data. Please try again later.</p>';
        }
    };

    // ... (The rest of the file, renderGrid function etc., remains the same) ...

    // Function to render the grid of cards
    const renderGrid = (items) => {
        gridContainer.innerHTML = ''; // Clear previous results

        if (items.length === 0) {
            gridContainer.innerHTML = '<p>No items match your criteria.</p>';
            return;
        }

        items.forEach(item => {
            const statusClass = item.is_available ? 'status-available' : 'status-unavailable';
            const statusText = item.is_available ? 'Available' : 'Out of Stock';

            const cardHTML = `
                <div class="book-card">
                    <div class="card-img">
                        <img src="${item.cover_image}" alt="${item.title}">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">${item.title}</h3>
                        <p class="card-author">by ${item.author_lecturer}</p>
                        <div class="card-status ${statusClass}">${statusText}</div>
                        <div class="card-footer">
                            <span class="card-copies">Copies: ${item.available_copies}/${item.total_copies}</span>
                            <a href="${options.baseUrl}/material_details.php?id=${item.id}" class="btn btn-primary btn-sm">More Details</a>
                        </div>
                    </div>
                </div>
            `;
            gridContainer.insertAdjacentHTML('beforeend', cardHTML);
        });
    };

    // Event listeners for real-time filtering
    filterForm.addEventListener('keyup', (e) => {
        if (e.target.matches('input[name="search"]')) {
            fetchMaterials();
        }
    });

    filterForm.addEventListener('change', (e) => {
        if (e.target.matches('select')) {
            fetchMaterials();
        }
    });

    // Initial load of all materials
    fetchMaterials();
}


const renderGrid = (items) => {
    gridContainer.innerHTML = ''; // Clear previous results

    if (items.length === 0) {
        gridContainer.innerHTML = '<p>No items match your criteria.</p>';
        return;
    }

    items.forEach(item => {
        const statusClass = item.is_available ? 'status-available' : 'status-unavailable';
        const statusText = item.is_available ? 'Available' : 'Out of Stock';

        const cardHTML = `
            <div class="book-card">
                <div class="card-img">
                    <img src="${item.cover_image}" alt="${item.title}">
                </div>
                <div class="card-content">
                    <h3 class="card-title">${item.title}</h3>
                    <p class="card-author">by ${item.author_lecturer}</p>
                    <div class="card-status ${statusClass}">${statusText}</div>
                    <div class="card-footer">
                        <span class="card-copies">Copies: ${item.available_copies}/${item.total_copies}</span>
                        <!-- THIS IS THE CORRECTED LINK -->
                        <a href="${options.baseUrl}/material_details.php?id=${item.id}" class="btn btn-primary btn-sm">More Details</a>
                    </div>
                </div>
            </div>
        `;
        gridContainer.insertAdjacentHTML('beforeend', cardHTML);
    });
};