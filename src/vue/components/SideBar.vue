<template>
    <nav class="navbar navbar-vertical  navbar-expand-md navbar-light" id="sidebar">

        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarCollapse"
                aria-controls="sidebarCollapse" aria-expanded="true" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Brand -->
        <a class="navbar-brand" :href="path+'/dashboard/seo-manager'">
            <img :src="path+'/vendor/lionix/img/logo.png'" class="navbar-brand-img
            mx-auto" alt="">
        </a>
        <div class="collapse navbar-collapse" id="sidebarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" :href="path+'/dashboard'">{{$t('main.back_dashboard')}} <span class="sr-only"></span></a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" @submit.prevent="submit($event)">
                <button class="btn btn-success my-2 my-sm-0" :disabled="loaded" type="submit">{{$t('main.sitemaps_generator')}}</button>
            </form>
        </div>
    </nav>
</template>

<script>
    import {mapGetters} from "vuex";

    export default {
        name: "SideBar",
        data(){
          return {
              loaded: false,
              importing : false,
          }
        },
        computed:{
          ...mapGetters(['path'])
        },
        methods:{
            submit(event){
                this.loaded = true;
                this.$http.get(API_URL + '/sitemaps-generators').then(response => {
                    this.routes = response.data.routes;
                    this.importing = false;
                    this.loaded = false;
                    this.$swal({
                        icon:"success",
                        title:this.$t('main.generated')
                    });
                });
            }
        }
    }
</script>
