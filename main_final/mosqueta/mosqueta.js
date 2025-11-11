/* ========= Configuración de dificultad ========= */
const DIFFICULTY = {
  easy: { swaps: 6, speed: 900 },
  normal: { swaps: 10, speed: 600 },
  hard: { swaps: 18, speed: 450 },
  impossible: { swaps: 28, speed: 390 },
};

/* ========= Mensajes ========= */
const WRONG_MESSAGES = [
  "¡Fallaste! 😢",
  "Te has equivocado.",
  "No fue la opción correcta.",
  "Esta vez no acertaste.",
  "Uy, no era ahí.",
  "¡Casi!",
  "¡Ni cerca, campeón!",
  "La bola estaba en otro vaso.",
  "Errar es parte del juego. ¡Sigue! 💪",
  "Ánimo, la próxima lo clavas!",
  "¡Le pifiaste!",
  "Te comiste el amague.",
  "Te patinó la bocha.",
  "¡Te falló la puntería!",
  "Te fuiste con la finta.",
  "¿Revanchita?",
  "¿Otra ronda para redimirte?",
];

const WIN_MESSAGES = [
  "¡Correcto! 🎉",
  "¡Excelente jugada!",
  "¡La clavaste!",
  "¡Bien ahí!",
  "¡Lo encontraste!",
  "¡Impecable!",
  "¡Genial, sigue así!",
  "¡Eso es habilidad!",
  "¡Durísimo! 💪",
  "¡Crack total!",
  "¡Maestro de la mosqueta!",
  "¡Te salió redondo!",
  "¡Golazo!",
  "¡Perfecto!",
  "¡Aplausos!",
  "¡Brillante!",
  "¡De diez!",
];

/* ========= Referencias DOM ========= */
const gameArea = document.querySelector(".game-board");
const cups = Array.from(document.querySelectorAll(".cup"));
const attemptsEl = document.getElementById("attempts");
const winsEl = document.getElementById("wins");
const streakEl = document.getElementById("streak");
const resultEl = document.getElementById("resultBanner");
const feedbackEl = document.getElementById("feedbackBanner");
const nextEl = document.getElementById("nextBanner");

/* ========= Estado ========= */
let currentDiff = "normal";
let stage = "hide";
let attempts = 0;
let wins = 0;
let streak = 0;
let ballIndex = null;

/* ========= Utilidades ========= */
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));
const rand = (arr) => arr[Math.floor(Math.random() * arr.length)];

const lockBoard = () => {
  gameArea.classList.add("no-hover");
  gameArea.style.pointerEvents = "none";
};

const enableClicksOnly = () => {
  gameArea.style.pointerEvents = "auto";
};

const unlockBoard = () => {
  gameArea.classList.remove("no-hover");
  gameArea.style.pointerEvents = "auto";
};

/* ========= Guardado ========= */
function guardarProgreso() {
  const body = new URLSearchParams({
    puntos: wins,
    intentos: attempts,
    dificultad: currentDiff,
    id_juego: "3",
  }).toString();

  return fetch("guardar_puntaje_mosqueta.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body,
  })
    .then((r) => r.text())
    .then((t) => console.log("Guardado:", t))
    .catch((err) => console.error("Error guardando:", err));
}

function guardarProgresoSync() {
  const body = `puntos=${wins}&intentos=${attempts}&dificultad=${currentDiff}&id_juego=3`;

  if (navigator.sendBeacon) {
    const blob = new Blob([body], {
      type: "application/x-www-form-urlencoded",
    });
    navigator.sendBeacon("guardar_puntaje_mosqueta.php", blob);
  } else {
    fetch("guardar_puntaje_mosqueta.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body,
      keepalive: true,
    }).catch(() => {});
  }
}

/* ========= Carteles ========= */
const showResult = (txt) => {
  resultEl.textContent = txt;
  resultEl.classList.remove("hidden");
  return sleep(1000).then(() => resultEl.classList.add("hidden"));
};

const showFeedback = (win) => {
  feedbackEl.textContent = win ? "¡Bien visto!" : "¡Muy lento!";
  feedbackEl.classList.remove("hidden");
  return sleep(1000).then(() => feedbackEl.classList.add("hidden"));
};

const showNextBanner = () => {
  lockBoard();
  nextEl.classList.remove("hidden");
  return sleep(1000).then(() => {
    nextEl.classList.add("hidden");
    unlockBoard();
  });
};

/* ========= Inicialización ========= */
cups.forEach((c) => c.addEventListener("click", handleCupClick));
initDifficultyMenu();

/* ========= Lógica principal ========= */
async function handleCupClick(e) {
  if (stage === "shuffling") return;

  const cup = e.currentTarget;
  const idx = cups.indexOf(cup);

  cup.classList.remove("raise", "lower");
  void cup.offsetWidth;
  cup.classList.add("raise");

  /* -------- Etapa 1: esconder pelota -------- */
  if (stage === "hide") {
    lockBoard();
    ballIndex = idx;
    await liftCup(cup, true);

    stage = "shuffling";
    gameArea.classList.add("shuffling");
    await sleep(300);
    await mixCups();
    gameArea.classList.remove("shuffling");

    enableClicksOnly();
    stage = "guess";
    return;
  }

  /* -------- Etapa 2: adivinar -------- */
  if (stage === "guess") {
    lockBoard();
    attempts++;
    attemptsEl.textContent = `Intentos: ${attempts}`;

    const win = idx === ballIndex;

    if (win) {
      wins++;
      winsEl.textContent = `Ganados: ${wins}`;
      streak++;
      streakEl.textContent = `Racha: ${streak}`;

      await liftCup(cup, true);
      await showResult(rand(WIN_MESSAGES));
    } else {
      streak = 0;
      streakEl.textContent = "Racha: 0";

      await Promise.all([liftCup(cup, false), liftCup(cups[ballIndex], true)]);

      await showResult(rand(WRONG_MESSAGES));
    }

    await showFeedback(win);

    /* ========= Guardado REAL de la ronda ========= */
    await guardarProgreso();

    await showNextBanner();
    resetRound();
    unlockBoard();
  }
}

/* ========= Animaciones ========= */
async function liftCup(cup, showBall) {
  cup.classList.remove("lower", "cover");
  cup.classList.add("raise");
  if (showBall) cup.classList.add("show-ball");

  await sleep(400);

  cup.classList.remove("raise");
  cup.classList.add("cover");
  await sleep(500);

  cup.classList.remove("cover");
  if (showBall) cup.classList.remove("show-ball");
}

async function mixCups() {
  const { swaps, speed } = DIFFICULTY[currentDiff];
  for (let n = 0; n < swaps; n++) {
    let i = Math.floor(Math.random() * 3);
    let j;
    do {
      j = Math.floor(Math.random() * 3);
    } while (j === i);

    await animateSwap(i, j, speed);

    if (ballIndex === i) ballIndex = j;
    else if (ballIndex === j) ballIndex = i;
  }
}

function animateSwap(i, j, duration) {
  return new Promise((res) => {
    const A = cups[i],
      B = cups[j];
    const dx = B.getBoundingClientRect().left - A.getBoundingClientRect().left;

    [A, B].forEach((el) => (el.style.transition = `transform ${duration}ms`));
    A.style.transform = `translateX(${dx}px)`;
    B.style.transform = `translateX(${-dx}px)`;

    setTimeout(() => {
      [A, B].forEach((el) => {
        el.style.transition = "";
        el.style.transform = "";
      });

      function swapNodes(a, b) {
        const parent = a.parentNode;
        if (!parent || parent !== b.parentNode) return;

        if (a.nextSibling === b) return parent.insertBefore(b, a);
        if (b.nextSibling === a) return parent.insertBefore(a, b);

        const aNext = a.nextSibling;
        const bNext = b.nextSibling;

        parent.insertBefore(a, bNext);
        parent.insertBefore(b, aNext);
      }

      swapNodes(A, B);
      [cups[i], cups[j]] = [cups[j], cups[i]];

      res();
    }, duration);
  });
}

function resetRound() {
  stage = "hide";
  cups.forEach((c) =>
    c.classList.remove("raise", "lower", "cover", "show-ball")
  );
}

/* ========= Menú dificultad ========= */
function initDifficultyMenu() {
  const btn = document.getElementById("difficultyBtn");
  const menu = document.getElementById("difficultyMenu");

  btn.addEventListener("click", () => {
    const open = btn.getAttribute("aria-expanded") === "true";
    btn.setAttribute("aria-expanded", !open);
    menu.classList.toggle("hidden");
  });

  menu.addEventListener("click", (e) => {
    if (!e.target.matches("[data-level]")) return;

    currentDiff = e.target.dataset.level;
    btn.textContent = `Dificultad: ${e.target.textContent} ▾`;

    menu.classList.add("hidden");
    btn.setAttribute("aria-expanded", "false");
  });

  document.addEventListener("click", (e) => {
    if (!menu.contains(e.target) && e.target !== btn) {
      menu.classList.add("hidden");
      btn.setAttribute("aria-expanded", "false");
    }
  });
}

/* ========= Guardar al salir ========= */
document.addEventListener("visibilitychange", () => {
  if (document.visibilityState === "hidden") guardarProgresoSync();
});

window.addEventListener("beforeunload", guardarProgresoSync);
