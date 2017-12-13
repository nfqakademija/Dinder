export const modalResize = function () {
    setTimeout(function() {
        const viewport = document.getElementById('viewport');
        const cards = viewport.getElementsByClassName('card');
        let height = 300;

        for (let i = 0; i < cards.length; i++) {
            if(cards[i].clientHeight > height) {
                height = cards[i].clientHeight;
            }
        }

        viewport.style.height = (height + 20) + 'px';
    }, 100);
};
