<template><div>
    <ui-axios method="get" action="/api/app/dashboard" #default="axios">
        <div class="row no-gutters align-items-center">
            <div class="col-6"><h2 class="text-uppercase">Contadores</h2></div>
            <div class="col-6">
                <div class="row">
                    <div class="col" v-for="c in axios.resp.counts">
                        <div class="text-center py-3 bg-white shadow-sm">
                            <div>{{ c.title }}</div>
                            <div style="font-size:35px;">{{ c.total }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ui-axios>
</div></template>

<script>
export default {
    layout: 'admin',
    middleware: 'auth',

    data() {
        return {
            // 
        }
    }
}
</script>