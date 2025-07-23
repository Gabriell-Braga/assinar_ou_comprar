<template><div class="ui-html">
    <textarea ref="textarea"></textarea>
    <!-- <pre>$data: {{ $data }}</pre> -->
</div></template>

<style>
.ui-html .ck-editor {border:solid 1px #eee;}
.ui-html .ck-toolbar, .ui-html .ck-editor__editable {border:none!important; box-shadow:none!important;}
</style>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    props: {
        value: {default:''},
    },

    watch: {
        $props: {deep:true, handler(value) {
            setTimeout(() => {
                this.props = Object.assign({}, value);
                if (this.ckeditor && !this.$el.contains(document.activeElement)) {
                    this.ckeditor.setData(this.props.value||'');
                }
            }, 10);
        }},
    },

    data() {
        return {
            ckeditor: false,
            props: Object.assign({}, this.$props),
        };
    },

    methods: {
        ckeditorInit() {
            let ckeditorSettings = {
                extraPlugins: [],
                codeBlock: {language:"javascript"},
                // toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
            };
            ClassicEditor.create(this.$refs.textarea, ckeditorSettings).then(editor => {
                this.ckeditor = editor;
                
                editor.setData(this.props.value||'');
                editor.model.document.on('change:data', () => {
                    if (! this.$el.contains(document.activeElement)) return;
                    this.props.value = editor.getData();
                    this.$emit('input', this.props.value);
                });
            });
        },
    },

    mounted() {
        this.ckeditorInit();
    },
};
</script>