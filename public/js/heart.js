const heartButtons = document.querySelectorAll('.heart-button');

heartButtons.forEach((button) => {
    const heartIcon = button.querySelector('.heart-icon');

    if (heartIcon.classList.contains('isheart')) {
        heartIcon.setAttribute('fill', 'currentColor');
    } else {
        heartIcon.setAttribute('fill', 'none');
    }

    button.addEventListener('click', function () {
        const productId = button.getAttribute('data-product-id')
        const isFilled = heartIcon.getAttribute('fill') === 'currentColor';
        if (isFilled) {
            const res = unlikeProduct(productId);
            if(res){
                heartIcon.setAttribute('fill', 'none');
                notyf.success('Xoá sản phẩm khỏi mục yêu thích thành công!');
            }
        } else {
            const res = likeProduct(productId);
            if(res){
                heartIcon.setAttribute('fill', 'currentColor');
                notyf.success('Thêm vào sản phẩm yêu thích thành công!');
            }
        }
    });
});

function likeProduct(productId) {
    return fetch('/api/user/like', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            ID_SP: productId,
        }),
    })
    .then(response => {
        if (!response.ok) {
            return false;
        }
        return response.json();
    })
    .then(data => {
        return data.success === true;
    })
    .catch(error => {
        return false;
    });
}

function unlikeProduct(productId) {
    return fetch('/api/user/unlike', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            ID_SP: productId,
        }),
    })
    .then(response => {
        if (!response.ok) {
            return false;
        }
        return response.json();
    })
    .then(data => {
        return data.success === true;
    })
    .catch(error => {
        return false;
    });
}

