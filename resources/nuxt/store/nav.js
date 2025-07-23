export default {
    state() {
        return {
            default: [
                {to:"/dashboard", title:"Dashboard", icon:"fas fa-home", children:[]},
                {to:"/admin/tevep", title:"Tevep", icon:"fas fa-shopping-bag", children:[
                    {to:"/admin/tevep", title:"Projetos", icon:"", children:[]},
                    {to:"/admin/tevep/0", title:"Criar", icon:"", children:[]},
                ]},
                {to:"", title:"Configurações", icon:"fas fa-cog", children:[
                    {to:"/user/me", title:"Meus dados", icon:"", children:[]},
                ]},
            ],
            
            admin: [
                {to:"/dashboard", title:"Dashboard", icon:"fas fa-home", children:[]},
                {to:"/admin/tevep", title:"Tevep", icon:"fas fa-shopping-bag", children:[
                    {to:"/admin/tevep", title:"Projetos", icon:"", children:[]},
                    {to:"/admin/tevep/0", title:"Criar", icon:"", children:[]},
                ]},
                {to:"", title:"Configurações", icon:"fas fa-cog", children:[
                    {to:"/user/me", title:"Meus dados", icon:"", children:[]},
                    {to:"/admin/settings", title:"Configurações", icon:"", children:[]},
                ]},
            ],
        };
    },

    mutations: {},
    
    actions: {},
};