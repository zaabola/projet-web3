// script.js
document.addEventListener('DOMContentLoaded', function () {
    // Define the data for the chart
    const destinations = [
        { name: 'Tozeur', rating: 90 },
        { name: 'Djerba', rating: 85 },
        { name: 'El Jem', rating: 92 },
        { name: 'Sidi bou said', rating: 88 },
        { name: 'Carthage', rating: 91 },
        { name: 'Tunis', rating: 87 },
        { name: 'Douz', rating: 89 },
        { name: 'Dougga', rating: 86 },
        { name: 'Kairouan', rating: 84 },
        { name: 'Ain drahem et Tbarka', rating: 90 }
    ];

    // Extract names and ratings
    const labels = destinations.map(dest => dest.name);
    const ratings = destinations.map(dest => dest.rating);

    // Create the chart
    const ctx = document.getElementById('travelChart').getContext('2d');
    const travelChart = new Chart(ctx, {
        type: 'line', // You can change this to 'bar', 'pie', etc.
        data: {
            labels: labels,
            datasets: [{
                label: 'Visitor Ratings (%)',
                data: ratings,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Visitor Rating (%)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Destinations'
                    }
                }
            },
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Travel Destination Visitor Ratings'
                }
            }
        }
    });
});