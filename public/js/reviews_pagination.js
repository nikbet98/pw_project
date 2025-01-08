document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('#pagination-container').addEventListener('click', function (e) {
        e.preventDefault();
        if (e.target.tagName === 'A') {
            fetchReviews(e.target.getAttribute('href'));
        }
    });
});

function fetchReviews(url) {
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(data => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');
        document.querySelector('#reviews-container').innerHTML = doc.querySelector('#reviews-container').innerHTML;
        document.querySelector('#pagination-container').innerHTML = doc.querySelector('#pagination-container').innerHTML;
    })
    .catch(error => console.error('Error fetching reviews:', error));
}