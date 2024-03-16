// close opened details
const details = document.querySelectorAll("details");
details.forEach((targetDetail) => {
  targetDetail.addEventListener("click", () => {
    details.forEach((detail) => {
      if (detail !== targetDetail) {
        detail.removeAttribute("open");
      }
    });
  });
});

window.onclick = function(e){
    if( e.target.matches(".site-result-popup") ){
        document.getElementById("site-result-popup").style.display = "none";
    }
}

function openPopup() {
    document.getElementById("site-result-popup").style.display = "block";
}

function closePopup() {
    document.getElementById("site-result-popup").style.display = "none";
}

jQuery(document).ready(function($) {
    // Function to show the preloader
    function showPreloader() {
        $('body').append('<div id="preloader"><div class="spinner"></div></div>');
    }
    // showPreloader();
    
    // Function to hide the preloader
    function hidePreloader() {
        $('#preloader').fadeOut('slow', function() {
            $(this).remove();
        });
    }
    

    // Hide the preloader when the page is fully loaded
    $(window).on('load', function() {
        hidePreloader();
    });

    // Intercept form submission to prevent reloading the page
    $('form').submit(function(e) {
        e.preventDefault();
        showPreloader();
        const inputValue = document.getElementById('custom_input').value;
    
        // Replace the current URL without adding to history
        window.history.replaceState({}, '', window.location.href.split('?')[0] + '?url=' + inputValue);
    
        // Simulate page load after form submission
        setTimeout(function() {
            window.location.reload();
        }, 1000); 
    });

});