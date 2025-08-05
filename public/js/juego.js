document.addEventListener('DOMContentLoaded', () => {
    const cardArray = [
        { name: 'recycle', img: '/images/cards/recycle.png' },
        { name: 'earth', img: '/images/cards/earth.png' },
        { name: 'plant', img: '/images/cards/plant.png' },
        { name: 'water', img: '/images/cards/water.png' },
        { name: 'trash', img: '/images/cards/trash.png' },
        { name: 'energy', img: '/images/cards/energy.png' },
        { name: 'recycle', img: '/images/cards/recycle.png' },
        { name: 'earth', img: '/images/cards/earth.png' },
        { name: 'plant', img: '/images/cards/plant.png' },
        { name: 'water', img: '/images/cards/water.png' },
        { name: 'trash', img: '/images/cards/trash.png' },
        { name: 'energy', img: '/images/cards/energy.png' },
    ];
    

    cardArray.sort(() => 0.5 - Math.random()); // Mezclar las cartas

    const board = document.querySelector('#game-board');
    let cardsChosen = [];
    let cardsChosenId = [];
    let cardsWon = [];

    // Crear el tablero
    function createBoard() {
        for (let i = 0; i < cardArray.length; i++) {
            const card = document.createElement('img');
            card.setAttribute('src', '/images/cards/blank.png'); // Imagen de reverso
            card.setAttribute('data-id', i);
            card.addEventListener('click', flipCard);
            board.appendChild(card);
        }
    }

    // Voltear la carta
    function flipCard() {
        let cardId = this.getAttribute('data-id');
        cardsChosen.push(cardArray[cardId].name);
        cardsChosenId.push(cardId);
        this.setAttribute('src', cardArray[cardId].img);
        if (cardsChosen.length === 2) {
            setTimeout(checkForMatch, 500);
        }
    }

    // Verificar si hay una pareja
    function checkForMatch() {
        const cards = document.querySelectorAll('#game-board img');
        const optionOneId = cardsChosenId[0];
        const optionTwoId = cardsChosenId[1];

        if (optionOneId == optionTwoId) {
            // Hiciste clic en la misma imagen
            cards[optionOneId].setAttribute('src', '/images/cards/blank.png');
            cards[optionTwoId].setAttribute('src', '/images/cards/blank.png');
            alert('¡Diste clic en la misma imagen!');
        } else if (cardsChosen[0] === cardsChosen[1]) {
            // Encontraste una pareja
            cards[optionOneId].setAttribute('src', '/images/cards/white.png');
            cards[optionTwoId].setAttribute('src', '/images/cards/white.png');
            cards[optionOneId].removeEventListener('click', flipCard);
            cards[optionTwoId].removeEventListener('click', flipCard);
            cardsWon.push(cardsChosen);
        } else {
            // No son iguales
            cards[optionOneId].setAttribute('src', '/images/cards/blank.png');
            cards[optionTwoId].setAttribute('src', '/images/cards/blank.png');
        }
        cardsChosen = [];
        cardsChosenId = [];

        if (cardsWon.length === cardArray.length / 2) {
            alert('¡Felicidades! Encontraste todas las parejas.');
            
            // Registrar el juego como completado
            fetch('/actividades/juego/finish', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    completed: true
                })
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                }
            })
            .catch(error => {
                console.error('Error al registrar el juego:', error);
            });
        }
    }

    // Reiniciar el juego
    document.getElementById('restart-button').addEventListener('click', () => {
        board.innerHTML = '';
        cardsWon = [];
        cardsChosen = [];
        cardsChosenId = [];
        cardArray.sort(() => 0.5 - Math.random());
        createBoard();
    });

    createBoard();
});
