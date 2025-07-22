<!-- Extra Large Modal -->
<div class="modal fade" id="edit_rich_text_model" tabindex="-1" role="dialog" aria-labelledby="edit_rich_text_model" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="edit_rich_text_form">
                @method('PUT')
                <input type="hidden" name="detail_id">
                <div class="block block-rounded shadow-none mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Update Rich Text</h3>
                        <div class="block-options">
                            <button type="reset" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <div class="row mb-4">
                            <div class="col-12 mb-4">
                                <label class="form-label" for="edit_rich_text_name">Name</label>
                                <input type="text" class="form-control" id="edit_rich_text_name" name="name" placeholder="Name..">
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="edit_rich_text_value">Value</label>
                                <textarea class="form-control" id="edit_rich_text_value" name="value"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm text-end border-top">
                        <button type="reset" class="btn btn-alt-secondary edit_rich_text_close" data-bs-dismiss="modal">
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
