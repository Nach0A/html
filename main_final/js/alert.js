// === script para el sweetalert ===
function zentryxAlert({ icon, title, text, confirmText = "Aceptar", goto = null }) {
    Swal.fire({
        icon,
        title,
        text,
        background: "rgba(20, 0, 40, 0.92)",
        color: "#fff",
        confirmButtonText: confirmText,
        customClass: { popup: "neon-border" },
        showClass: { popup: "swal2-show" },
        hideClass: { popup: "swal2-hide" },
        backdrop: "rgba(0, 0, 0, 0.6)"
    }).then(() => {
        if (goto) setTimeout(() => { window.location.href = goto; }, 350);
    });
}
