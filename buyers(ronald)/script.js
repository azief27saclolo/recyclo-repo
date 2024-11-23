function getUrlParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

document.addEventListener('DOMContentLoaded', () => {
    const productName = getUrlParam('productName');
    const productLocation = getUrlParam('location');
    const productPrice = getUrlParam('price');
    const metalType = getUrlParam('metalType') || "Metal"; 
    const imageUrls = [getUrlParam('imageUrl1'), getUrlParam('imageUrl2'), getUrlParam('imageUrl3')];

    if (productName) document.getElementById('product-name').textContent = productName;
    if (productLocation) document.getElementById('product-location').textContent = productLocation;
    if (productPrice) document.getElementById('product-price').textContent = productPrice;
    if (metalType) document.getElementById('metal-type').textContent = metalType;

    const slides = document.querySelectorAll('.slide');
    slides.forEach((slide, index) => {
        if (imageUrls[index]) {
            slide.querySelector('img').src = imageUrls[index];
        }
    });

    let currentSlideIndex = 0;

    function changeSlide(direction) {
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        slides[currentSlideIndex].classList.remove('active');

        currentSlideIndex = (currentSlideIndex + direction + totalSlides) % totalSlides;
        slides[currentSlideIndex].classList.add('active');
    }

    setInterval(() => {
        changeSlide(1);
    }, 5000);

    const quantityInput = document.getElementById('quantity-input');
    
    document.getElementById("minus-btn").addEventListener("click", function() {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    });

    document.getElementById("plus-btn").addEventListener("click", function() {
        let currentValue = parseInt(quantityInput.value);
        quantityInput.value = currentValue + 1;
    });
    document.querySelector('.add-to-cart-btn').addEventListener('click', () => {
        alert(`${productName} added to the cart!`);
    });

    document.querySelector('.prev').addEventListener('click', () => changeSlide(-1));
    document.querySelector('.next').addEventListener('click', () => changeSlide(1));
});