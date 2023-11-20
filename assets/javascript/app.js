$(document).ready(function () {
  // Function to load content based on the hash in the URL
  function loadContent(route) {
    $.ajax({
      url: "../../includes/load_content.php", // Replace with your PHP script URL
      method: "POST",
      data: { route: route },
      cache: false,
      contentType: false,
      processData: false,
      success: function (response) {
        $("#content").html(response);

        alert(response);
      },
    });
  }

  // Initial page load based on the current URL hash
  loadContent(window.location.hash);

  // Handle navigation link clicks
  $("#navLinks").on("click", "a", function (event) {
    event.preventDefault();
    var route = $(this).attr("href");
    alert(route);
    window.location.hash = route;
    loadContent(route);
  });

  // Handle hash change events (back/forward buttons)
  $(window).on("hashchange", function () {
    var route = window.location.hash;
    loadContent(route);
  });
});
