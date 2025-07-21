<!-- Extra Large Modal -->
<div class="modal fade" id="edit_title_model" tabindex="-1" role="dialog" aria-labelledby="edit_title_model" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="edit_title_form">
                @method('PUT')
                <input type="hidden" name="detail_id">
                <div class="block block-rounded shadow-none mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Update Title</h3>
                        <div class="block-options">
                            <button type="reset" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="edit_title_name">Name</label>
                                <input type="text" class="form-control" id="edit_title_name" name="name" placeholder="Name..">
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="edit_title_value">Value</label>
                                <input type="text" class="form-control" id="edit_title_value" name="value" placeholder="Value..">
                            </div>
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm text-end border-top">
                        <button type="reset" class="btn btn-alt-secondary edit_title_close" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-alt-primary">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Extra Large Modal -->
