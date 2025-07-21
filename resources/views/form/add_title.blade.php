<!-- Extra Large Modal -->
<div class="modal fade" id="add_title_model" tabindex="-1" role="dialog" aria-labelledby="add_title_model" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="add_title_form">
                <div class="block block-rounded shadow-none mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Add Title</h3>
                        <div class="block-options">
                            <button type="reset" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <div class="row mb-4">
                            <div class="col-6">
                                <label class="form-label" for="add_title_name">Name</label>
                                <input type="text" class="form-control" id="add_title_name" name="name" placeholder="Name..">
                            </div>
                            <div class="col-6">
                                <label class="form-label" for="add_title_value">Value</label>
                                <input type="text" class="form-control" id="add_title_value" name="value" placeholder="Value..">
                            </div>
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm text-end border-top">
                        <button type="reset" class="btn btn-alt-secondary add_title_close" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-alt-primary">
                            Add
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- END Extra Large Modal -->
