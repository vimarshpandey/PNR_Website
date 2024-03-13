document.addEventListener("DOMContentLoaded", function()
{
    const links = document.querySelectorAll('nav a');

    links.forEach(link => { link.addEventListener('click', scrollToSection); });

    function scrollToSection(e)
    {
        e.preventDefault();

        const targetId = this.getAttribute('href').substring(1);
        const targetSection = document.getElementById(targetId);

        window.scrollTo
        ({
            top: targetSection.offsetTop,
            behavior: 'smooth'
        });
    }
});