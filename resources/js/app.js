import AOS from "aos";
import "aos/dist/aos.css";
import "./bootstrap";

document.addEventListener("DOMContentLoaded", () => {
    AOS.init({
        once: true,
        offset: 50,
        duration: 800,
        easing: "ease-out-cubic",
    });

    const btn = document.getElementById("mobile-menu-btn");
    const menu = document.getElementById("mobile-menu");
    const iconPath = btn?.querySelector("path"); // Seleciona o desenho dentro do SVG

    // Desenhos dos ícones (Hambúrguer vs X)
    const paths = {
        hamburger: "M3 4H21V6H3V4ZM3 11H21V13H3V11ZM3 18H21V20H3V18Z",
        close: "M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z",
    };

    if (btn && menu) {
        const toggleMenu = (forceClose = false) => {
            const isClosed = menu.classList.contains("max-h-0");

            if (forceClose && isClosed) return;

            menu.classList.toggle("max-h-0");
            menu.classList.toggle("opacity-0");
            menu.classList.toggle("max-h-96");
            menu.classList.toggle("opacity-100");

            const isOpen = !menu.classList.contains("max-h-0");
            btn.setAttribute("aria-expanded", isOpen);

            if (iconPath) {
                iconPath.setAttribute(
                    "d",
                    isOpen ? paths.close : paths.hamburger
                );
            }
        };

        btn.addEventListener("click", () => toggleMenu());

        menu.addEventListener("click", (e) => {
            if (e.target.closest("a")) {
                toggleMenu(true);
            }
        });

        window.addEventListener("resize", () => {
            if (window.innerWidth >= 768) toggleMenu(true);
        });
    }
});
