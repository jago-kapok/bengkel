<div class="card card-primary card-outline">
    <form action="<?= ($BASE.'/model/create') ?>" method="POST" enctype="multipart/form-data">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-md-3 col-form-label">Model Code</label>
              <div class="col-md-9">
                <input name="model_code" class="form-control form-control-sm">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-md-3 col-form-label">Model Desc</label>
              <div class="col-md-9">
                <input name="model_desc" class="form-control form-control-sm">
              </div>
            </div>
          </div>

          </div>
        </div>
      </div>
      <div class="card-footer bg-whitesmoke">
        <button type="submit" name="create" class="btn btn-primary btn-sm btn-form"><b>Simpan</b></button>
        <a href="../../model" class="btn btn-danger btn-sm btn-form"><b>Kembali</b></a>
      </div>
    </form>
  </div>