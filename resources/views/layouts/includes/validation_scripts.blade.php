<script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/plugins/jquery-validation/additional-methods.js') }}"></script>
<script>
    $(function() {
        $.validator.addMethod("filesize", function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param);
        }, "File size must be within the allowed limit.");

        $.validator.addMethod("notEqual", function(value, element, param) {
            return value !== $(param).val();
        }, "Both values must be different.");
    });
</script>
