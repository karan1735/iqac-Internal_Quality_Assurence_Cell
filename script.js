function toggleMenu() {
  var nav = document.querySelector("nav");
  nav.classList.toggle("active");

  // Add animation to hamburger icon
  const hamburger = document.querySelector(".hamburger");
  hamburger.classList.toggle("active");

  // Close menu when clicking outside
  document.addEventListener("click", function closeMenu(e) {
    if (!nav.contains(e.target) && !hamburger.contains(e.target)) {
      nav.classList.remove("active");
      hamburger.classList.remove("active");
      document.removeEventListener("click", closeMenu);
    }
  });
}

fetch("navbar.php")
  .then((response) => response.text())
  .then((data) => {
    document.getElementById("navbar").innerHTML = data;

    // Get current URL path
    const currentPage = window.location.pathname.split("/").pop();

    // Get all navbar links
    const navLinks = document.querySelectorAll(".nav-link");

    // Loop through links to find a match with current page
    navLinks.forEach((link) => {
      if (link.getAttribute("href") === currentPage) {
        link.classList.add("active"); // Add 'active' class to the current page link
      }
    });
  });
$(".carousel").carousel();

// Load the header
fetch("header.php")
  .then((response) => response.text())
  .then((data) => {
    document.getElementById("header").innerHTML = data;
  });

fetch("footer.html")
  .then((response) => response.text())
  .then((data) => {
    document.getElementById("footer").innerHTML = data;
  });
