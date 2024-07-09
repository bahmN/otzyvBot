// get elements
const button = document.getElementById('screenBtn');
const loader = document.querySelector('.loader');


let isLoading = false;

Array.from(document.getElementsByClassName('screenBtn'))
    .forEach((element) => {
        element.addEventListener('click', () => {
            console.log('clicked')

            if (!isLoading) {
                startLoadingAnimation();
            } else if (isLoading) {
                stopLoadingAnimation();
            }
            changeButtonText();
        }

        );
    }
    );

// start animation
function startLoadingAnimation() {
    isLoading = true;
    loader.style.display = 'flex';
    loader.classList.add('rotate');
}