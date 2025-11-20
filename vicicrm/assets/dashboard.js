/* ============================================================
   DASHBOARD JS HELPER FUNCTIONS
============================================================ */

function animateCounters() {
    document.querySelectorAll(".kpi-value").forEach(el => {
        let target = parseInt(el.innerText);
        let count = 0;
        let step = Math.max(1, Math.floor(target / 40));

        let interval = setInterval(() => {
            count += step;
            if (count >= target) {
                count = target;
                clearInterval(interval);
            }
            el.innerText = count;
        }, 20);
    });
}

/* animate only after DOM is loaded */
document.addEventListener("DOMContentLoaded", animateCounters);
