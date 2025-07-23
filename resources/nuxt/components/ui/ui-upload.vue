<template><div class="ui-upload" dropzone="copy" @drop="onDrop($event);" @dragover="$event.preventDefault()">
    <div class="d-flex align-items-stretch bg-white shadow-sm m-0" @click="openBrowser()" style="position:relative;">
        <div class="bg-secondary" v-html="backgroundImage"></div>
        <div class="flex-grow-1 p-3 pl-0 d-flex align-items-center">
            <div class="flex-grow-1">
                <div v-if="props.value">{{ props.value|filename }}</div>
                <div v-else><i class="fas fa-upload"></i> &nbsp; Upload</div>
                <div class="progress mt-1" style="height:5px;" v-if="upload.running">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" :style="`width:${upload.progress}%`"></div>
                </div>
            </div>
        </div>
    </div>
</div></template>


<style>
.ui-upload {cursor:pointer;}
</style>


<script>export default {
    name: "ui-upload",

    props: {
        value: {default: ''},
        action: {default: '/api/upload'},
    },

    watch: {
        $props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    computed: {
        file() {
            let type = {
                family: false,
                format: false,
            };

            if (this.props.value.startsWith('data:')) {
                let f = this.props.value.split(/[^a-zA-Z0-9]/);
                type.family = f[1];
                type.format = f[2];
            }

            return type;
        },

        backgroundImage() {
            let ext = this.props.value.split('.').pop().toLowerCase();
            let isImage = ['jpg', 'jpeg', 'bmp', 'png', 'gif', 'webp'].indexOf(ext)>=0;
            let backgroundImage = !isImage? '': `background:url(${window.location.origin}/${this.props.value}) center center no-repeat; background-size:cover;`;
            let inner = isImage? '': `<i class="far fa-file-${ext}" style="padding:7px 0 0 7px; font-size:20px;"></i>`;
            return `<div style="width:72px; height:72px; ${backgroundImage}">${inner}</div>`;
        },
    },

    filters: {
        filename(value) {
            return value.split('/').pop();
        },
    },

    methods: {
        emit() {
            this.$emit('input', this.props.value);
        },

        onDrop(ev) {
            ev.preventDefault();
            this.getFile(ev.dataTransfer.files[0]||false);
        },

        openBrowser() {
            let input = Object.assign(document.createElement('input'), {
                type: 'file',
                onchange: (ev) => {
                    this.doUpload(ev.target.files[0]||false);
                },
            }).click();
        },

        getFile(file) {
            if (! file) return;

            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                this.props.value = reader.result;
                this.$emit('input', this.props.value);
            };
        },

        doUpload(file) {
            this.upload.progress = 0;

            const data = new FormData();
            data.append("file", file, file.name);
            this.$axios.post(this.props.action, data, {
                onUploadProgress: (ev) => {
                    this.upload.running = true;
                    this.upload.progress = Math.round((ev.loaded * 100) / ev.total);
                },
            }).then(resp => {
                this.upload.running = false;
                this.upload.progress = 100;
                this.upload.mime = resp.data.mime||false;
                this.upload.error = resp.data.error||false;
                this.props.value = resp.data.path;
                this.$emit('input', this.props.value);
            });
        },
    },

    data() {
        return {
            upload: {running:false, progress:0, mime:false, error:false},
            props: Object.assign({}, this.$props),
        };
    },
};</script>
