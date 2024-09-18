<script>
    $(document).ready(function() {
        $("#main-form").on("submit", function(e) {
            e.preventDefault();

            NProgress.start();

            const username = $("#username").val();
            const password = $("#password").val();
            const remember = ($("#remember").is(":checked")).toString();

            const base_url = '<?= base_url() ?>';

            $.ajax({
                type: "POST",
                url: base_url + "api/login/process/",
                data: JSON.stringify({
                    username: username,
                    password: password,
                    remember: remember
                }),
                contentType: "json",
                processData: false,
                success: function(response) {
                    if (response.status == 200) {
                        toastr.success("<b>Success</b><br>User verified successfully");
                        toastr.info("<b>Info</b><br>Redirecting to the dashboard, please wait");
                        location.href = base_url;
                    } else {
                        toastr.warning("<b>Warning</b><br>" + response.message);
                    }
                    NProgress.done();
                },
                error: function(xhr) {
                    toastr.error("<b>Error</b><br> Internal Server Error");
                    NProgress.done();
                }
            });
        });
    });
</script>