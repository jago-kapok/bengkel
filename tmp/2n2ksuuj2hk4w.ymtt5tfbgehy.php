<script>

    $(document).ready(function () {
        var table = $('#model_table').DataTable({
            "scrollX": false,
            "ordering": false,
            "scrollCollapse": true,
            "processing": true,
            "serverSide": true,
            "lengthChange": false,
            "searching": true,
            "ajax": {
                "url": "<?= ($BASE.'/model/data') ?>",
                "type": "POST"
            },
            "columns": [
                { data: 0 }, { data: 1 },
                {
                    "render": function (data, type) {
                        return '<a href="<?= ($BASE."/model/update/' + data + '") ?>" class="badge badge-info p-1" title="Perbarui Data"><i class="fa fa-pen"></i></>';
                    }
                }
            ]
        });

        $('#model_search').on('keyup', function () {
            table.search(this.value).draw();
        });
    });
</script>