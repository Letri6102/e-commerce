
document.addEventListener('DOMContentLoaded', () => {
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const reviewItems = document.querySelectorAll('.review-item');
    let currentVisible = 5; 

    if (reviewItems.length <= currentVisible) {
        loadMoreBtn.classList.add('hidden'); 
    }

    loadMoreBtn.addEventListener('click', () => {
        const nextVisible = currentVisible + 5; 
        reviewItems.forEach((item, index) => {
            if (index < nextVisible) {
                item.classList.remove('hidden'); 
            }
        });
        currentVisible = nextVisible; 

        if (currentVisible >= reviewItems.length) {
            loadMoreBtn.classList.add('hidden');
        }
    });

    const loadMoreBtn2 = document.getElementById('loadMoreBtn2');
    const commentItems = document.querySelectorAll('.comment-item');
    let currentVisiblecmt = 5; 

    if (commentItems.length <= currentVisiblecmt) {
        loadMoreBtn2.classList.add('hidden'); 
    }

    loadMoreBtn2.addEventListener('click', () => {
        const nextVisible = currentVisiblecmt + 5; 
        commentItems.forEach((item, index) => {
            if (index < nextVisible) {
                item.classList.remove('hidden'); 
            }
        });
        currentVisiblecmt = nextVisible; 

        if (currentVisiblecmt >= commentItems.length) {
            loadMoreBtn2.classList.add('hidden');
        }
    });
});

