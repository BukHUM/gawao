window.addEventListener('load', function () {
    let mobileMenu = document.getElementById('mobile-menu')
    let mobileMenuToggle = document.getElementById('primary-menu-toggle')

    if(mobileMenu && mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function (e) {
            e.preventDefault()
            mobileMenu.classList.toggle('hidden')
        })
    }
})
