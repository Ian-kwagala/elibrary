// --- Hamburger Menu for Mobile ---
const hamburger = document.querySelector(".hamburger");
const navMenu = document.querySelector(".nav-menu");

if (hamburger && navMenu) {
    hamburger.addEventListener("click", () => {
        hamburger.classList.toggle("active");
        navMenu.classList.toggle("active");
    });

    document.querySelectorAll(".nav-link").forEach(n => n.addEventListener("click", () => {
        hamburger.classList.remove("active");
        navMenu.classList.remove("active");
    }));
}

// --- Smooth Scroll-in Animation for Sections ---
const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('show');
        } else {
            // Optional: remove the class to re-trigger animation on scroll up
            // entry.target.classList.remove('show'); 
        }
    });
});

// Select all elements you want to animate
const hiddenElements = document.querySelectorAll('.hidden');
// Observe each of them
hiddenElements.forEach((el) => observer.observe(el));

// --- Notification Popup Logic ---
function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification-popup ${type}`;
    notification.textContent = message;

    // Append to body
    document.body.appendChild(notification);

    // Show the notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 10); // a small delay to trigger transition

    // Hide and remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 500); // wait for fade out transition
    }, 3000);
}