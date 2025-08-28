// === WGO Plugin Frontend Script ===

document.addEventListener('DOMContentLoaded', function () {
    // ✅ Conferma ritiro con feedback visivo
    const confirmForm = document.querySelector('.wgo-confirm-form');
    if (confirmForm) {
        confirmForm.addEventListener('submit', function () {
            const button = confirmForm.querySelector('input[type="submit"]');
            button.disabled = true;
            button.value = 'Confermato ✔';
        });
    }

    // 📦 Evidenzia badge "Da ritirare"
    const pendingBadges = document.querySelectorAll('.wgo-badge-pending');
    pendingBadges.forEach(function (badge) {
        badge.style.animation = 'wgoPulse 1.5s infinite';
    });

    // 🛒 Validazione quantità nei form ordine
    const orderForm = document.querySelector('.wgo-order-form form');
    if (orderForm) {
        orderForm.addEventListener('submit', function (e) {
            const inputs = orderForm.querySelectorAll('input[type="number"]');
            let valid = false;
            inputs.forEach(function (input) {
                if (parseInt(input.value) > 0) valid = true;
            });
            if (!valid) {
                e.preventDefault();
                alert('Seleziona almeno un prodotto per partecipare all’ordine.');
            }
        });
    }
});

// 💡 Animazione badge "Da ritirare"
const style = document.createElement('style');
style.innerHTML = `
@keyframes wgoPulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.6; }
    100% { transform: scale(1); opacity: 1; }
}
`;
document.head.appendChild(style);
