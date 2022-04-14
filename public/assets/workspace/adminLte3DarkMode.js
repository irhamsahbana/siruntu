// toggle dark mode with jquery
$('#toggle-dark-mode').click(function() {
    $('body').toggleClass('dark-mode');
    $('.navbar').toggleClass('navbar-dark');
    $('.navbar').toggleClass('navbar-light');
    $('.navbar').toggleClass('navbar-white');

    // when dark-mode is on, change the toggle button to light mode
    if ($('body').hasClass('dark-mode')) {
      $(this).html('<i class="fas fa-sun"></i>');
      localStorage.setItem('dark-mode', 'true');
    } else {
      $(this).html('<i class="fas fa-moon"></i>');
      localStorage.setItem('dark-mode', 'false');
    }
  });

  // check if dark-mode is on or off
  if (localStorage.getItem('dark-mode') == 'true') {
    $('body').addClass('dark-mode');
    $('.navbar').addClass('navbar-dark');
    $('.navbar').removeClass('navbar-light');
    $('.navbar').removeClass('navbar-white');
    $('#toggle-dark-mode').html('<i class="fas fa-sun"></i>');
  } else {
    $('body').removeClass('dark-mode');
    $('.navbar').removeClass('navbar-dark');
    $('.navbar').addClass('navbar-light');
    $('.navbar').addClass('navbar-white');
    $('#toggle-dark-mode').html('<i class="fas fa-moon"></i>');
  }