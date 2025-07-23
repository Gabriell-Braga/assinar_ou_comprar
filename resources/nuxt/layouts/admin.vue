<!-- https://coreui.io/demo/3.2.0/ -->

<script>
export default {
    watch: {
        $route: {deep:true, handler(value) {
            this.showNav = false;
        }},
    },

    data() {
        return {
            topNav: false,
            toggle: false,
            showNav: JSON.parse(localStorage.getItem('coreUiShowNav')||'false'),
            adminMenuIndex: null,
            notifications: [],
        };
    },

    computed: {
        navItems() {
            let level = this.$store.state.auth.user.level;
            let nav = this.$store.state.nav;
            return (level? nav[level]: nav['default']);
        },
    },

    // async fetch() {
    //     this.notifications = await this.$axios.get('/api/user/notifications?seen=0').then(res => res.data);
    // },

    methods: {
        logout() {
            this.$auth.logout().then((resp) => {
                this.$router.push({path: '/auth/'});
            });
        },

        toggleSet(name) {
            this.toggle = (this.toggle==name? false: name);
        },

        toggleNav(params={}) {
            this.showNav = !this.showNav;

            params = Object.assign({
                save: true,
            }, params);

            if (params.save) {
                localStorage.setItem('coreUiShowNav', JSON.stringify(this.showNav));
            }
        },

        toggleClass(ev, params={}) {
            params = Object.assign({
                target: ev.target,
                class: 'test',
            }, params);

            let element = (evv, target) => {
                return new Promise((resolve, reject) => {
                    ((elem) => {
                        if (typeof elem=='object' && elem.nodeName) { return [elem]; }
                        if (elem=='parent') { return [evv.target.parentNode]; }
                        else { return document.querySelectorAll(elem); }
                        return [];
                    })(target).forEach(resolve);
                });
            };

            element(ev, params.target).then(target => {
                target.classList.toggle(params.class);
            });
        },
    },

    mounted() {
        // this.$push('user_notifications:created', (resp) => {
        //     this.$axios.get('/api/user-notification/search').then(resp => {
        //         console.log(resp.data);
        //     });
        // });
    },
};</script>


<!-- https://modularcode.io/modular-admin-html/ -->
<template><div class="layout-admin">
    <div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar" :class="{'c-sidebar-unfoldable':showNav, 'c-sidebar-show':showNav}">
        <div class="c-sidebar-brand d-md-down-none">
            <svg class="c-sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
                <use xlink:href="assets/brand/coreui-pro.svg#full"></use>
            </svg>
            <svg class="c-sidebar-brand-minimized" width="46" height="46" alt="CoreUI Logo">
                <use xlink:href="assets/brand/coreui-pro.svg#signet"></use>
            </svg>
        </div>

        <ul class="c-sidebar-nav">
            <li class="c-sidebar-nav-title">Menu</li>
            
            <template v-for="(m,i) in navItems">
                <li class="c-sidebar-nav-item" v-if="m.children.length==0">
                    <nuxt-link :to="m.to" class="c-sidebar-nav-dropdown-toggle" :class="{'c-active':i==adminMenuIndex}" @click.native="adminMenuIndex=null">
                        <span class="c-sidebar-nav-icon">
                            <i :class="m.icon"></i>
                        </span> {{ m.title }}
                    </nuxt-link>
                </li>
                
                <li class="c-sidebar-nav-dropdown" :class="{'c-show':i==adminMenuIndex}" v-else>
                    <a href="javascript:;" class="c-sidebar-nav-dropdown-toggle" :class="{'c-active':i==adminMenuIndex}" @click="adminMenuIndex=i">
                        <span class="c-sidebar-nav-icon">
                            <i :class="m.icon"></i>
                        </span> {{ m.title }}
                    </a>

                    <ul class="c-sidebar-nav-dropdown-items">
                        <li class="c-sidebar-nav-item" v-for="(mm,ii) in m.children">
                            <nuxt-link :to="mm.to" class="c-sidebar-nav-link"> {{ mm.title }}</nuxt-link>
                        </li>
                    </ul>
                </li>
            </template>
            
            <li class="c-sidebar-nav-divider"></li>
        </ul>

        <!-- <pre>{{ navItems }}</pre> -->

        <button class="c-sidebar-minimizer c-class-toggler" type="button" @click="toggleNav();"></button>
    </div>
    <div class="c-sidebar-backdrop c-fade c-show" v-if="showNav" @click="showNav=false"></div>

    <div class="c-sidebar c-sidebar-lg c-sidebar-light c-sidebar-right c-sidebar-overlaid" id="aside" ref="aside" :class="{'c-sidebar-show':toggle=='aside'}">
        <button class="c-sidebar-close c-class-toggler" type="button" @click="toggleSet(false)" responsive="true">
            <div class="c-icon"><i class="fas fa-times"></i></div>
        </button>
        <ul class="nav nav-tabs nav-underline nav-underline-primary" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#timeline" role="tab">
                    <svg class="c-icon">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-list"></use>
                    </svg>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#messages" role="tab">
                    <svg class="c-icon">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-speech"></use>
                    </svg>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#settings" role="tab">
                    <svg class="c-icon">
                    <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-settings"></use>
                    </svg>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="timeline" role="tabpanel">
                <div class="list-group list-group-accent">
                    <div class="list-group-item list-group-item-accent-secondary bg-light text-center font-weight-bold text-muted text-uppercase c-small">Today</div>
                    <div class="list-group-item list-group-item-accent-warning list-group-item-divider">
                    <div class="c-avatar float-right"><img class="c-avatar-img" src="assets/img/avatars/7.jpg" alt="user@email.com"></div>
                    <div>Meeting with <strong>Lucas</strong></div>
                    <small class="text-muted mr-3">
                        <svg class="c-icon">
                            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-calendar"></use>
                        </svg>
                        &nbsp; 1 - 3pm
                    </small>
                    <small class="text-muted">
                        <svg class="c-icon">
                            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-location-pin"></use>
                        </svg>
                        &nbsp; Palo Alto, CA
                    </small>
                    </div>
                    <div class="list-group-item list-group-item-accent-info">
                    <div class="c-avatar float-right"><img class="c-avatar-img" src="assets/img/avatars/4.jpg" alt="user@email.com"></div>
                    <div>Skype with <strong>Megan</strong></div>
                    <small class="text-muted mr-3">
                        <svg class="c-icon">
                            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-calendar"></use>
                        </svg>
                        &nbsp; 4 - 5pm
                    </small>
                    <small class="text-muted">
                        <svg class="c-icon">
                            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-skype"></use>
                        </svg>
                        &nbsp; On-line
                    </small>
                    </div>
                    <div class="list-group-item list-group-item-accent-secondary bg-light text-center font-weight-bold text-muted text-uppercase c-small">Tomorrow</div>
                    <div class="list-group-item list-group-item-accent-danger list-group-item-divider">
                    <div>New UI Project - <strong>deadline</strong></div>
                    <small class="text-muted mr-3">
                        <svg class="c-icon">
                            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-calendar"></use>
                        </svg>
                        &nbsp; 10 - 11pm
                    </small>
                    <small class="text-muted">
                        <svg class="c-icon">
                            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-home"></use>
                        </svg>
                        &nbsp; creativeLabs HQ
                    </small>
                    <div class="c-avatars-stack mt-2">
                        <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="assets/img/avatars/2.jpg" alt="user@email.com"></div>
                        <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="assets/img/avatars/3.jpg" alt="user@email.com"></div>
                        <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="assets/img/avatars/4.jpg" alt="user@email.com"></div>
                        <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="assets/img/avatars/5.jpg" alt="user@email.com"></div>
                        <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="assets/img/avatars/6.jpg" alt="user@email.com"></div>
                    </div>
                    </div>
                    <div class="list-group-item list-group-item-accent-success list-group-item-divider">
                    <div><strong>#10 Startups.Garden</strong> Meetup</div>
                    <small class="text-muted mr-3">
                        <svg class="c-icon">
                            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-calendar"></use>
                        </svg>
                        &nbsp; 1 - 3pm
                    </small>
                    <small class="text-muted">
                        <svg class="c-icon">
                            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-location-pin"></use>
                        </svg>
                        &nbsp; Palo Alto, CA
                    </small>
                    </div>
                    <div class="list-group-item list-group-item-accent-primary list-group-item-divider">
                    <div><strong>Team meeting</strong></div>
                    <small class="text-muted mr-3">
                        <svg class="c-icon">
                            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-calendar"></use>
                        </svg>
                        &nbsp; 4 - 6pm
                    </small>
                    <small class="text-muted">
                        <svg class="c-icon">
                            <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-home"></use>
                        </svg>
                        &nbsp; creativeLabs HQ
                    </small>
                    <div class="c-avatars-stack mt-2">
                        <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="assets/img/avatars/2.jpg" alt="user@email.com"></div>
                        <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="assets/img/avatars/3.jpg" alt="user@email.com"></div>
                        <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="assets/img/avatars/4.jpg" alt="user@email.com"></div>
                        <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="assets/img/avatars/5.jpg" alt="user@email.com"></div>
                        <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="assets/img/avatars/6.jpg" alt="user@email.com"></div>
                        <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="assets/img/avatars/7.jpg" alt="user@email.com"></div>
                        <div class="c-avatar c-avatar-xs"><img class="c-avatar-img" src="assets/img/avatars/8.jpg" alt="user@email.com"></div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane p-3" id="messages" role="tabpanel">
                <div class="message">
                    <div class="py-3 pb-5 mr-3 float-left">
                    <div class="c-avatar"><img class="c-avatar-img" src="assets/img/avatars/7.jpg" alt="user@email.com"><span class="c-avatar-status bg-success"></span></div>
                    </div>
                    <div><small class="text-muted">Lukasz Holeczek</small><small class="text-muted float-right mt-1">1:52 PM</small></div>
                    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                    <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
                </div>
                <hr>
                <div class="message">
                    <div class="py-3 pb-5 mr-3 float-left">
                    <div class="c-avatar"><img class="c-avatar-img" src="assets/img/avatars/7.jpg" alt="user@email.com"><span class="c-avatar-status bg-success"></span></div>
                    </div>
                    <div><small class="text-muted">Lukasz Holeczek</small><small class="text-muted float-right mt-1">1:52 PM</small></div>
                    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                    <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
                </div>
                <hr>
                <div class="message">
                    <div class="py-3 pb-5 mr-3 float-left">
                    <div class="c-avatar"><img class="c-avatar-img" src="assets/img/avatars/7.jpg" alt="user@email.com"><span class="c-avatar-status bg-success"></span></div>
                    </div>
                    <div><small class="text-muted">Lukasz Holeczek</small><small class="text-muted float-right mt-1">1:52 PM</small></div>
                    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                    <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
                </div>
                <hr>
                <div class="message">
                    <div class="py-3 pb-5 mr-3 float-left">
                    <div class="c-avatar"><img class="c-avatar-img" src="assets/img/avatars/7.jpg" alt="user@email.com"><span class="c-avatar-status bg-success"></span></div>
                    </div>
                    <div><small class="text-muted">Lukasz Holeczek</small><small class="text-muted float-right mt-1">1:52 PM</small></div>
                    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                    <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
                </div>
                <hr>
                <div class="message">
                    <div class="py-3 pb-5 mr-3 float-left">
                    <div class="c-avatar"><img class="c-avatar-img" src="assets/img/avatars/7.jpg" alt="user@email.com"><span class="c-avatar-status bg-success"></span></div>
                    </div>
                    <div><small class="text-muted">Lukasz Holeczek</small><small class="text-muted float-right mt-1">1:52 PM</small></div>
                    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                    <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
                </div>
            </div>
            <div class="tab-pane p-3" id="settings" role="tabpanel">
                <h6>Settings</h6>
                <div class="c-aside-options">
                    <div class="clearfix mt-4"><small><b>Option 1</b></small>
                    <label class="c-switch c-switch-label c-switch-pill c-switch-success c-switch-sm float-right">
                    <input class="c-switch-input" type="checkbox" checked=""><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                    </label>
                    </div>
                    <div><small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small></div>
                </div>
                <div class="c-aside-options">
                    <div class="clearfix mt-3"><small><b>Option 2</b></small>
                    <label class="c-switch c-switch-label c-switch-pill c-switch-success c-switch-sm float-right">
                    <input class="c-switch-input" type="checkbox"><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                    </label>
                    </div>
                    <div><small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small></div>
                </div>
                <div class="c-aside-options">
                    <div class="clearfix mt-3"><small><b>Option 3</b></small>
                    <label class="c-switch c-switch-label c-switch-pill c-switch-success c-switch-sm float-right">
                    <input class="c-switch-input" type="checkbox"><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                    </label>
                    </div>
                </div>
                <div class="c-aside-options">
                    <div class="clearfix mt-3"><small><b>Option 4</b></small>
                    <label class="c-switch c-switch-label c-switch-pill c-switch-success c-switch-sm float-right">
                    <input class="c-switch-input" type="checkbox" checked=""><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                    </label>
                    </div>
                </div>
                <hr>
                <h6>System Utilization</h6>
                <div class="text-uppercase mb-1 mt-4"><small><b>CPU Usage</b></small></div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small class="text-muted">348 Processes. 1/4 Cores.</small>
                <div class="text-uppercase mb-1 mt-2"><small><b>Memory Usage</b></small></div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small class="text-muted">11444GB/16384MB</small>
                <div class="text-uppercase mb-1 mt-2"><small><b>SSD 1 Usage</b></small></div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small class="text-muted">243GB/256GB</small>
                <div class="text-uppercase mb-1 mt-2"><small><b>SSD 2 Usage</b></small></div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small class="text-muted">25GB/256GB</small>
            </div>
        </div>
    </div>
    <div class="c-wrapper">
        <header class="c-header c-header-light c-header-fixed align-items-center">
            <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" @click="toggleNav({save:false});">
                <div class="c-icon c-icon-lg"><i class="fas fa-bars"></i></div>
            </button>

            <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" responsive="true" @click="toggleNav();">
                <div class="c-icon c-icon-lg"><i class="fas fa-bars"></i></div>
            </button>
            
            <!--
            <ul class="c-header-nav d-md-down-none">
                <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="#">Dashboard</a></li>
                <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="#">Users</a></li>
                <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="#">Settings</a></li>
            </ul>
            -->
            
            <ul class="c-header-nav mfs-auto">
                <li class="c-header-nav-item px-3 c-d-legacy-none">
                    <button class="c-class-toggler c-header-nav-btn" type="button" id="header-tooltip" data-target="body" data-class="c-dark-theme" data-toggle="c-tooltip" data-placement="bottom" title="Toggle Light/Dark Mode">
                    <svg class="c-icon c-d-dark-none">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-moon"></use>
                    </svg>
                    <svg class="c-icon c-d-default-none">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-sun"></use>
                    </svg>
                    </button>
                </li>
            </ul>

            <ul class="c-header-nav">

                <!-- Notifications -->
                <li class="c-header-nav-item dropdown">
                    <ui-axios v-bind="{method:'get', action:'/api/user-notification/search', push:'user-notifications:created'}" #default="axios">
                        <a href="javascript:;" class="c-header-nav-link" role="button" @click="topNav=(topNav=='notifications'? false: 'notifications')">
                            <div class="c-icon"><i class="fas fa-bell"></i></div>
                            <span class="badge badge-pill badge-danger" v-html="axios.resp.total" v-if="axios.resp.total && axios.resp.total>0"></span>
                        </a>
                        
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg pt-0" style="margin-top:20px; width:300px;" :class="{show:topNav=='notifications'}">
                            <div class="dropdown-header bg-light"><strong>Você tem {{ axios.resp.total||0 }} {{ axios.resp.total==1? 'notificação': 'notificações' }}</strong></div>
                            <div style="max-height:300px; overflow:auto;">
                                <nuxt-link :to="`/user/notifications/${n.id}`" class="dropdown-item" v-for="n in axios.resp.data" :key="n.id">
                                    <div class="d-flex align-items-center">
                                        <div v-if="n.image"><div :style="`width:35px; height:35px; background:url(${n.image}) center center no-repeat; background-size:cover; border-radius:4px;`"></div></div>
                                        <div class="pl-2">
                                            <div v-html="n.title" style="white-space:initial!important;"></div>
                                        </div>
                                    </div>
                                </nuxt-link>
                            </div>
                            <!-- <div class="dropdown-header bg-light"><strong>Server</strong></div> -->
                            <!-- <nuxt-link :to="`/user/notifications/`" class="dropdown-item font-weight-bold">
                                Ver todas as notificações
                            </nuxt-link> -->
                        </div>
                    </ui-axios>
                </li>

                <li class="c-header-nav-item dropdown">
                    <a href="javascript:;" class="c-header-nav-link" role="button" @click="toggleSet('userOptions')" style="text-decoration:none;">
                        {{ $auth.user.name }} &nbsp; <div class="c-avatar" :title="$auth.user.name"><img class="c-avatar-img" :src="$auth.user.meta.photo" :alt="$auth.user.name" v-if="$auth.user.meta"></div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right pt-0" style="margin-top:10px;" ref="userOptions" :class="{show:toggle=='userOptions'}" @click="toggleSet(false)">
                        <div class="dropdown-header bg-light py-2"><strong>Conta</strong></div>
                        <nuxt-link to="/user/me/" class="dropdown-item">
                            <div class="c-icon mfe-2"><i class="fas fa-user"></i></div>
                            Meus dados
                        </nuxt-link>
                        <a href="javascript:;" class="dropdown-item" @click="logout()">
                            <div class="c-icon mfe-2"><i class="fas fa-power-off"></i></div>
                            Logout
                        </a>
                    </div>
                </li>

                <!-- Right sidenav
                <button class="c-header-toggler c-class-toggler mfe-md-3" type="button" @click="toggleSet('aside')">
                    <div class="c-icon c-icon-lg"><i class="fas fa-ellipsis-v"></i></div>
                </button>
                -->
            </ul>

            <!-- <div class="c-subheader justify-content-between px-3">
                <ol class="breadcrumb border-0 m-0 px-0 px-md-3">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
                <div class="c-subheader-nav d-md-down-none mfe-2">
                    <a class="c-subheader-nav-link" href="#">
                    <svg class="c-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-speech"></use>
                    </svg>
                    </a>
                    <a class="c-subheader-nav-link" href="#">
                    <svg class="c-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-graph"></use>
                    </svg>
                    &nbsp;Dashboard
                    </a>
                    <a class="c-subheader-nav-link" href="#">
                    <svg class="c-icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-settings"></use>
                    </svg>
                    &nbsp;Settings
                    </a>
                </div>
            </div> -->
        </header>

        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    <div class="fade-in">
                        <nuxt></nuxt>
                    </div>
                </div>
            </main>
        </div>
        
        <footer class="c-footer d-none d-md-flex">
            <div><a href="https://coreui.io">CoreUI</a> © 2020 creativeLabs.</div>
            <div class="mfs-auto">Powered by&nbsp;<a href="https://coreui.io/pro/">CoreUI Pro</a></div>
        </footer>
    </div>
</div></template>

<style>
@import url('https://fonts.googleapis.com/css?family=Exo');
* {font-family: 'Exo', sans-serif;}

/* Custom scrollbar */
*::-webkit-scrollbar {width:15px; height:15px;}
*::-webkit-scrollbar-track {background:transparent;}
*::-webkit-scrollbar-thumb {background:transparent; border-radius:6px; box-shadow: inset 0 0 10px 10px var(--gray); border: solid 3px transparent;}
</style>