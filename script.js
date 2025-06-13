// Initialize Particles.js
document.addEventListener('DOMContentLoaded', function() {
    particlesJS('particles-js', {
        particles: {
            number: { value: 80, density: { enable: true, value_area: 800 } },
            color: { value: "#6c5ce7" },
            shape: { type: "circle" },
            opacity: { value: 0.5, random: true },
            size: { value: 3, random: true },
            line_linked: { enable: true, distance: 150, color: "#6c5ce7", opacity: 0.4, width: 1 },
            move: { enable: true, speed: 2, direction: "none", random: true, straight: false, out_mode: "out" }
        },
        interactivity: {
            detect_on: "canvas",
            events: {
                onhover: { enable: true, mode: "repulse" },
                onclick: { enable: true, mode: "push" }
            }
        }
    });

    // Typing Animation
    const typed = new Typed('.typing', {
        strings: ["Websites.", "Apps.", "AI.", "The Future."],
        typeSpeed: 80,
        backSpeed: 50,
        loop: true
    });

    // Navbar Scroll Effect
    const nav = document.querySelector('nav');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });

    // Mobile Menu Toggle
    const burger = document.querySelector('.burger');
    const navLinks = document.querySelector('.nav-links');

    burger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
        burger.classList.toggle('toggle');
    });

    // Smooth Scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
            navLinks.classList.remove('active');
            burger.classList.remove('toggle');
        });
    });

    // Scroll-Triggered Animations (Intersection Observer)
    const animateOnScroll = () => {
        // Skills Animation
        const skillCards = document.querySelectorAll('.skill-card');
        // Projects Animation
        const projectCards = document.querySelectorAll('.project-card');

        const observerOptions = {
            threshold: 0.2, // Trigger when 20% of element is visible
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                    observer.unobserve(entry.target); // Stop observing after animation
                }
            });
        }, observerOptions);

        // Observe Skill Cards
        skillCards.forEach((card) => {
            observer.observe(card);
        });

        // Observe Project Cards
        projectCards.forEach((card) => {
            observer.observe(card);
        });
    };

    // Initialize scroll animations
    animateOnScroll();

    // Skill Bar Animation (now triggered on scroll)
    const animateSkillBars = () => {
        const skillBars = document.querySelectorAll('.skill-level');
        
        const barObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const width = entry.target.style.width;
                    entry.target.style.width = '0';
                    setTimeout(() => {
                        entry.target.style.width = width;
                    }, 300);
                    barObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        skillBars.forEach((bar) => {
            barObserver.observe(bar);
        });
    };

    animateSkillBars();
});

