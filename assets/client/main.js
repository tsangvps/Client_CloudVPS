window.loadJS = function () {
    // Hiển thị/ẩn dropdown menu khi click vào avatar
    document.querySelectorAll('.btn-sm.dropdown-toggle').forEach(toggle => {
        // console.log(toggle)
        toggle.addEventListener("click", function (event) {
            event.stopPropagation();
            toggle.nextElementSibling.classList.toggle('show');
        });
    });

    // Đóng dropdown menu khi click ra ngoài
    document.addEventListener("click", function (e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
        }
    });
};

document.addEventListener('DOMContentLoaded', function() {
    // Ẩn/Hiện mật khẩu
    document.querySelectorAll('.input-group-append.password').forEach(button => {
        button.addEventListener("click", function () {
            const passwordField = button.previousElementSibling;
            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);
        });
    });

    // Xử lý đóng modal
    const modal = document.getElementById("modal-feedback");
    modal.querySelector(".close").addEventListener("click", function(){
        modal.classList.remove('show');
    });
    
    // Menu bên
    document.querySelectorAll('.side-nav .side-menu-main').forEach(menuItem => {
        menuItem.addEventListener('click', function () {
            const submenu = menuItem.nextElementSibling;
            const isOpen = submenu.style.display === 'block';
            const subIcon = menuItem.querySelector('.side-menu__sub-icon svg');

            submenu.style.display = isOpen ? 'none' : 'block';
            if (subIcon) {
                subIcon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        });
    });
    
    // Menu di động
    document.getElementById("mobile-menu-toggler").addEventListener("click", function () {
        document.querySelector(".mobile-menu ul").classList.toggle("hidden");
    });

    // Menu con
    document.querySelectorAll(".down-menu").forEach(menuItem => {
        menuItem.addEventListener("click", function () {
            const submenu = menuItem.nextElementSibling;
            const isOpen = submenu.style.display === 'block';
            const subIcon = menuItem.querySelector('.menu__title svg');

            submenu.style.display = isOpen ? 'none' : 'block';
            if (subIcon) {
                subIcon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        });
    });
    
    // Hiển thị/ẩn dropdown menu khi click vào avatar
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
        // console.log(toggle)
        toggle.addEventListener("click", function (event) {
            event.stopPropagation();
            toggle.nextElementSibling.classList.toggle('show');
        });
    });

    // Đóng dropdown menu khi click ra ngoài
    document.addEventListener("click", function (e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
        }
    });
});