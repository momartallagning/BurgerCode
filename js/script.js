/* TABLE */
$(document).ready( function () {
    $('#table_id').DataTable({

    dom: 'B<"clear">lfrtip',
    buttons: {
        name: 'primary',
        extend: ['copy', 'csv', 'excel', 'pdf']

        }}
    );
});