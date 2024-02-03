<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<style>
    .nav-link {
        display: block;
        padding: 0.5rem 1rem;
        color: rgba(0, 0, 0, .55);
        text-decoration: none;
        transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out;
    }

    .nav-link:focus,
    .nav-link:hover {
        color: var(--purple);
    }
</style>

<?php


if ($_SESSION['CT'] == 1) {
?>
    <div class="container">
        <ul class="nav nav-tabs ">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="#items"><span class="fas fa-list"></span> Item List</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#additem"><span class="fas fa-plus"></span> Add Item</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#itemreq"><span class="fas fa-comment-dots"></span> Cross-Chapter Request</a>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="#itemstrg"><span class="fa-solid fa-warehouse text-muted"></span> Item Location </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#itemcrit"><span class="fa-solid fa-triangle-exclamation text-muted"></span> Item Critical </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#itemcateg"><span class="fa-solid fa-layer-group text-muted"></span> Item Categories </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#chapters"><span class="fa-solid fa-city text-muted"></span> Chapters </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#audit"><span class="fa-solid fa-book text-muted"></span> Audit </a>
            </li>
        </ul>
        <div id="content">
            <!--// *Content will be dynamically loaded here -->
        </div>
    </div>
<?php  } ?>



<script>
    $(document).ready(function() {
        // Function to load content based on the hash in the URL
        function loadContent(route) {


            $.ajax({
                url: './includes/load_content.php', // Replace with your PHP script URL
                method: 'POST',
                data: {
                    route: route
                },
                success: function(response) {
                    $('#content').html(response);
                }
            });
        }

        // Initial page load based on the current URL hash
        loadContent(window.location.hash);

        // Handle navigation link clicks
        $('#navLinks').on('click', 'a', function(event) {
            event.preventDefault();
            var route = $(this).attr('href');
            window.location.hash = route;
            loadContent(route);



        });

        // Handle hash change events (back/forward buttons)
        $(window).on('hashchange', function() {
            var route = window.location.hash;
            loadContent(route);
        });
        $(document).ready(function() {
            $('table').DataTable();
        });

        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        });

        $('#myModal').on('shown.bs.modal', function() {
            $('#myInput').trigger('focus')
        });
    });
</script>

<script>

</script>