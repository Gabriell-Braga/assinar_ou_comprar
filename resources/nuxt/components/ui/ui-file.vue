<template><div class="ui-file">
    <div class="row no-gutters">
        <div class="col-12 col-md-3 col-lg-2 border bg-light">
            <a href="javascript:;" class="d-block py-1 px-2" @click="props.folder=''; listFiles()">
                <i class="fa fa-fw fa-folder"></i>
                ..
            </a>

            <a href="javascript:;" class="d-block py-1 px-2" v-for="f in files" :key="f.name" v-if="f.is_folder" @click="props.folder=f.name; listFiles()">
                <i class="fa fa-fw fa-folder" v-if="f.is_folder"></i>
                <i class="fa fa-fw fa-file" v-if="!f.is_folder"></i>
                {{ f.name }}
            </a>
        </div>

        <div class="col-12 col-md-9 col-lg-10 border border-left-0">
            <div class="row no-gutters">
                <div class="col-6 col-md-3 col-lg-2 p-2" v-for="f in files" :key="f.name" v-if="!f.is_folder">
                    <div @click="toggle(f)" :class="{'border border-primary':selected(f)}">
                        <div>
                            <img :src="f.url" alt="" style="width:100%; height:100px; object-fit:cover;" v-if="f.is_image">
                            <div class="text-center p-3" v-else-if="f.is_folder"><i class="fa fa-fw fa-folder" style="font-size:68px;"></i></div>
                            <div class="text-center p-3" v-else><i class="fa fa-fw fa-file" style="font-size:68px;"></i></div>
                        </div>
                        <div class="text-center bg-dark p-1">{{ f.name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div></template>

<script>
import TimeAgo from 'vue2-timeago';

export default {
    components: {TimeAgo},

    props: {
        value: {default:()=>([])},
        folder: {default:''},
    },

    watch: {
		$props: {deep:true, handler(value) {
            this.props = Object.assign({}, value);
        }},
    },

    data() {
        return {
            files: [],
            props: Object.assign({}, this.$props),
        };
    },

    methods: {
        listFiles() {
            let params = {folder:this.props.folder};
            this.$axios.get('/api/file/list', {params}).then(resp => {
                this.files = resp.data.files;
            });
        },

        toggle(item) {
            if (item.is_folder) return;
            let value = Array.isArray(this.props.value)? this.props.value: [];
            let index = value.indexOf(item);
            index>=0? value.splice(index, 1): value.push(item);
            this.props.value = value;
            this.$emit('input', this.props.value);
        },

        selected(item) {
            return this.props.value.indexOf(item)>=0;
        },
    },

    mounted() {
        this.listFiles();
    },
};</script>