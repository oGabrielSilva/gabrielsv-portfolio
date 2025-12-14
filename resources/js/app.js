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

    const mobileMenuBtn = document.getElementById("mobile-menu-btn");
    const mobileMenu = document.getElementById("mobile-menu");

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener("click", () => {
            mobileMenu.classList.toggle("hidden");
        });
    }
});
