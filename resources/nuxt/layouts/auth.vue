<template><div class="l-auth">
  <div class="row no-gutters">
    <div class="col-12 col-sm-4 col-md-7 d-none d-sm-flex l-auth-background" style="height:100vh;">
      &nbsp;
    </div>

    <div class="col-12 col-sm-8 col-md-5 d-flex align-items-center l-auth-pattern" style="height:100vh;">
      <div class="mx-auto px-5" style="width:100%; max-width:500px;">

        <div class="bg-white p-2 mb-2"><nuxt /></div>

        <div class="bg-white d-block p-2">
          <div><nuxt-link to="/auth">Fazer login</nuxt-link></div>
          <div><nuxt-link to="/auth/register">Criar conta</nuxt-link></div>
          <div><nuxt-link to="/auth/password">Recuperar senha</nuxt-link></div>
        </div>

      </div>
    </div>
  </div>
</div></template>

<style>
.l-auth-background {
  /* background: url(https://source.unsplash.com/random/800x600/?free,human&r=6) center center no-repeat; */
  background: url(https://source.unsplash.com/collection/1534020/800x600) center center no-repeat;
  background-size: cover;
}

.l-auth-pattern {
  background-color: #FFFFFF44;
  background-image:  radial-gradient(#444CF744 1px, transparent 1px), radial-gradient(#444CF744 1px, #FFFFFF44 1px);
  background-size: 40px 40px;
  background-position: 0 0,20px 20px;
}
</style>
