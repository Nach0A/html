// Lógica de selección, temporizador y control de pares

let timerInterval;
let startTime;
let matches = 0;
const totalPairs = 12;

// Referencias al DOM
const timerEl = document.getElementById('timer');
const matchesEl = document.getElementById('matches');
const boardEl = document.querySelector('.game-board');
const restartBtn = document.getElementById('restart-btn');
const winModal = document.getElementById('win-modal');
const finalTimeEl = document.getElementById('final-time');
const playAgainBtn = document.getElementById('play-again-btn');
// const homeBtn = document.getElementById('home-btn'); // Para futura navegación

// Inicialización del juego
function initGame() {
    // Reiniciar contadores
    matches = 0;
    matchesEl.textContent = `Pares: ${matches}`;
    resetTimer();
    startTimer();
    winModal.classList.add('hidden');

    // Generar cartas aleatorias
    const cards = generateDeck();
    boardEl.innerHTML = '';
    cards.forEach(cardData => boardEl.appendChild(createCardElement(cardData)));
}

// Genera array de 24 cartas mezcladas
function generateDeck() {
    const deck = [];
    for (let i = 1; i <= 12; i++) {
        deck.push({ id: `copa-${i}`, value: i });
        deck.push({ id: `copa-${i}`, value: i });
    }
    return shuffle(deck);
}

// Fisher–Yates shuffle (algoritmo de mezclado)
function shuffle(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

// Crea elemento DOM de carta || si se quieren implementar otro tipo de cartas de debe modificar este metodo 
function createCardElement({value }) {
    const card = document.createElement('div');
    card.className = 'card';
    card.dataset.value = value;

    card.innerHTML = `
    <div class="card-inner">
      <div class="card-front"></div>
      <div class="card-back" style="background: url('img/${value}-de-copa.jpg') center/contain no-repeat;"></div>
    </div>`;

    card.addEventListener('click', onCardClick);
    return card;
}

let firstCard = null;

function onCardClick(e) {
    const clicked = e.currentTarget;
    if (clicked.classList.contains('flipped') || firstCard && secondCard) return;

    clicked.classList.add('flipped');
    if (!firstCard) {
        firstCard = clicked;
    } else {
        const secondCard = clicked;
        if (firstCard.dataset.value === secondCard.dataset.value) {
            matches++;
            matchesEl.textContent = `Pares: ${matches}`;
            if (matches === totalPairs) showWinModal();
            firstCard = null;
        } else {
            setTimeout(() => {
                firstCard.classList.remove('flipped');
                secondCard.classList.remove('flipped');
                firstCard = null;
            }, 1000);
        }
    }
}

// Temporizador en formato mm:ss
function startTimer() {
    startTime = Date.now();
    timerInterval = setInterval(() => {
        const elapsed = Date.now() - startTime;
        const minutes = String(Math.floor(elapsed / 60000)).padStart(2, '0');
        const seconds = String(Math.floor((elapsed % 60000) / 1000)).padStart(2, '0');
        timerEl.textContent = `${minutes}:${seconds}`;
    }, 500);
}

function resetTimer() {
    clearInterval(timerInterval);
    timerEl.textContent = '00:00';
}

function showWinModal() {
    clearInterval(timerInterval);
    finalTimeEl.textContent = timerEl.textContent;
    winModal.classList.remove('hidden');
}

// Eventos
restartBtn.addEventListener('click', initGame);
playAgainBtn.addEventListener('click', initGame);

// Arrancar al cargar
window.addEventListener('load', initGame);