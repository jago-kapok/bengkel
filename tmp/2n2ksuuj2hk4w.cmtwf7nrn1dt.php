<div class="card card-primary card-outline">
    <form action="<?= ($BASE.'/customer/create') ?>" method="POST" enctype="multipart/form-data">
      <div class="card-body">
        <div class="row">
          <div class="col-md-10">
            <div class="form-group row">
              <label class="col-md-3 col-form-label">Customer Code</label>
              <div class="col-md-9">
                <input name="customer_code" class="form-control form-control-sm">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-form-label">Customer Name</label>
              <div class="col-md-9">
                <input name="customer_name" class="form-control form-control-sm">
              </div>
            </div>
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Customer Address</label>
                <div class="col-md-9">
                  <input name="customer_address" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Customer City</label>
                <div class="col-md-9">
                  <input name="customer_city" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Customer Phone</label>
                <div class="col-md-9">
                  <input name="customer_phone" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Customer Name Other</label>
                <div class="col-md-9">
                  <input name="customer_name_other" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Customer Address Other</label>
                <div class="col-md-9">
                  <input name="customer_address_other" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Customer City Other</label>
                <div class="col-md-9">
                  <input name="customer_city_other" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Customer Phone Other</label>
                <div class="col-md-9">
                  <input name="customer_phone_other" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-3 col-form-label">Customer Note</label>
                <div class="col-md-9">
                  <input name="customer_note" class="form-control form-control-sm">
                </div>
              </div>
          </div>

          </div>
        </div>
      </div>
      <div class="card-footer bg-whitesmoke">
        <button type="submit" name="create" class="btn btn-primary btn-sm btn-form"><b>Simpan</b></button>
        <a href="../../customer" class="btn btn-danger btn-sm btn-form"><b>Kembali</b></a>
      </div>
    </form>
  </div>