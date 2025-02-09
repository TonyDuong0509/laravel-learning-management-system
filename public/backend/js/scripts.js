/*!
 * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
 * Copyright 2013-2023 Start Bootstrap
 * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
 */
//
// Scripts
//

window.addEventListener("DOMContentLoaded", (event) => {
    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector("#sidebarToggle");
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener("click", (event) => {
            event.preventDefault();
            document.body.classList.toggle("sb-sidenav-toggled");
            localStorage.setItem(
                "sb|sidebar-toggle",
                document.body.classList.contains("sb-sidenav-toggled")
            );
        });
    }

    const tableList = document.querySelector("#datatable");
    const deleteForm = document.querySelector(".delete-form");
    tableList.addEventListener("click", (event) => {
        if (event.target.classList.contains("delete-action")) {
            event.preventDefault();
            Swal.fire({
                title: "Bạn có chắc chắn?",
                text: "Nếu xoá bạn không thể khôi phục!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ok, đồng ý xoá!",
            }).then((result) => {
                if (result.isConfirmed) {
                    const action = event.target.href;
                    deleteForm.action = action;
                    deleteForm.submit();
                }
            });
        }
    });
});
