<script>

  $(document).ready(function () {
    var table = $('#item_table').DataTable({
      "scrollX": false,
      "ordering": false,
      "scrollCollapse": true,
      "processing": true,
      "serverSide": true,
      "lengthChange": false,
      "searching": true,
      "ajax": {
        "url": "<?= ($BASE.'/item/data') ?>",
        "type": "POST"
      },
      "columns": [
        { data: 0 }, { data: 1 }, { data: 2 }, { data: 3 }, { data: 4 }, { data: 5 }, { data: 6 }, { data: 7 }, { data: 8 }, { data: 9 }, { data: 10 }, { data: 11 }, { data: 12 }, { data: 13 },
        {
          "render": function (data, type, full) {
            return '<a href="../../ui/assets/img/item/' + full[13] + '" class="badge badge-success p-1" title="Lihat Produk" target="_blank"><i class="fa fa-image"></i></a>&nbsp;<a href="<?= ($BASE."/item/update/' + data + '") ?>" class="badge badge-info p-1" title="Perbarui Data"><i class="fa fa-pen"></i></a>';
          }
        }
      ]
    });

    $('#item_search').on('keyup', function () {
      table.search(this.value).draw();
    });
  });
</script>