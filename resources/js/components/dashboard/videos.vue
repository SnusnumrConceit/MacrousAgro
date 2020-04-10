<template>
    <v-card height="100%" class="flex-card-full-size">
        <v-card-title>
            Видеогалерея
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col cols="6" v-for="(video, index) in videos" :key="video.id" height="100%" class="flex-card-full-size">
                    <v-card>
                        <v-card-text>
                            <video :src="video.path" controls height="240" width="320"></video>
                        </v-card-text>
                        <v-card-title class="text-center">
                            {{ video.title }}
                        </v-card-title>
                    </v-card>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script>
  import debounce from '../../debounce';

  export default {
    name: "videos",

    data() {
      return {
        videos: [],

        pagination: {
          last_page: 1,
          page: 1
        }
      }
    },


    methods: {
      async loadVideos() {
        const response = await axios.get(`${this.$attrs.apiRoute}/videos`, {
          params: {
            page: this.page
          }
        });

        this.videos = response.data.videos.data;
        this.pagination.last_page = response.data.videos.last_page;
      },

      onSearch() {
        if (this.search.keyword.length < 3) {
          return ;
        }

        this.searchData(this);
      },

      searchData: debounce((vm) => {
        axios.get(`${vm.$attrs.apiRoute}/videos/search`, {
          params: {
            page: vm.pagination.page,
          }
        })
            .then(response => {
              vm.videos = response.data.videos.data;
              vm.pagination.last_page = response.data.videos.last_page;
            })
            .catch(error => {
              vm.$swal(vm.$t('swal.title.error'), error.data.msg, 'error');
            })
        ;
      }, 300),

      onScroll() {
        this.paginationScroll(this, document);
      },

      paginationScroll: debounce((vm, element) => {
        if (vm.loading) {
          return ;
        }

        let atTheBottom = false;
        let scrollTop = (element === document) ? element.documentElement.scrollTop : element.scrollTop;
        let viewportHeight = (element === document) ? window.innerHeight : element.offsetHeight;
        let totalHeight = (element === document) ? element.documentElement.offsetHeight : element.scrollHeight;

        atTheBottom = scrollTop + viewportHeight === totalHeight;

        if (atTheBottom && vm.pagination.page !== vm.pagination.last_page) {
          vm.pagination.page++;
          vm.is_search ? vm.searchData(vm) : vm.loadVideos();
        }
      }, 300),
    },

    created() {
      this.loadVideos();
    },

    mounted() {
      document.addEventListener('scroll', this.onScroll);
    }
  }
</script>

<style scoped>
    .flex-card-full-size {
        display: flex;
        flex-direction: column;
    }
</style>