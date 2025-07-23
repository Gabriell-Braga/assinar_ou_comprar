<template><div>
    <ui-form method="post" action="/api/auth/password-token" v-model="user" v-if="!tokenGenerated" @success="tokenGenerated=true">
        <template #default="{error, loading}">
            <div class="input-group">
                <input type="text" class="form-control" v-model="user.email" placeholder="Informe seu e-mail">
                <div class="input-group-append"><div class="input-group-btn">
                    <button type="submit" class="btn btn-primary">
                        <span v-if="loading" v-html="loading"></span>
                        <span v-else>Enviar</span>
                    </button>
                </div></div>
                <small class="text-danger" v-if="error.token" v-html="error.email"></small>
            </div>
        </template>
    </ui-form>

    <ui-form method="post" action="/api/auth/password-reset" v-model="user" v-if="tokenGenerated && !passwordChanged" @success="passwordChanged=true">
        <template #default="{error}">
            <div class="form-group">
                <label>Informe o token recebido por e-mail</label>
                <div class="input-group">
                    <input type="text" class="form-control" v-model="user.token" placeholder="Informe o token recebido">
                    <div class="input-group-append"><div class="input-group-btn">
                        <button type="button" class="btn btn-primary" @click="tokenInformed=!!user.token">Ok</button>
                    </div></div>
                </div>
                <small class="text-danger" v-if="error.token" v-html="error.token"></small>
            </div>

            <div v-if="tokenInformed && user.token">
                <div class="form-group">
                    <label>Informe sua nova senha</label>
                    <ui-password v-model="user.password"></ui-password>
                    <small class="text-danger" v-if="error.password" v-html="error.password"></small>
                </div>
                
                <div class="form-group">
                    <label>Repita a senha</label>
                    <ui-password v-model="user.password_confirmation" :meter="false"></ui-password>
                    <small class="text-danger" v-if="error.password_confirmation" v-html="error.password_confirmation"></small>
                </div>
    
                <button type="submit" class="btn btn-primary btn-block">
                    Concluir
                </button>
            </div>
        </template>
    </ui-form>

    <div class="text-center" v-if="passwordChanged">
        Senha alterada. Por favor, faça login
    </div>
</div></template>

<script>export default {
    props: {
        value: {default:false},
    },

    // watch: {
    //     $props: {
    //         deep: true,
    //         handler(value) {
    //             this.props = Object.assign({}, value);
    //         },
    //     },
    // },

    methods: {
        passwordToken() {
            this.$axios.post('/api/auth/password-token', this.post).then((resp) => {
                if (resp.data.error) { return this.error = resp.data.error; }
                this.tokenGenerated = true;
            });
        },
        
        passwordReset() {
            this.error = {};
            this.$axios.post('/api/auth/password-reset', this.post).then((resp) => {
                if (resp.data.error) { return this.error = resp.data.error; }
                this.post = {email: '',password: '',password_confirmation: '',token: ''};
                this.$emit('success', resp.data);
            });
        },
    },

    data() {
        return {
            tokenGenerated: false,
            tokenInformed: false,
            passwordChanged: false,
            error: {},
            user: {email:'', token:'', password:'', password_confirmation:''},
        };
    },
};</script>