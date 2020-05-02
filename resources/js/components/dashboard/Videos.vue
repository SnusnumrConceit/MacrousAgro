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
                            <video :src="video.src" controls height="240" width="320"></video>
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
  import scroll from '../../mixins/infinite_scroll';

  export default {
    name: "videos",

    mixins: [scroll],

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
      async getVideos() {
        const response = await axios.get(`${this.$attrs.apiRoute}/videos`, {
          params: {
            page: this.pagination.page
          }
        });

        this.videos = (this.pagination.page === 1)
            ? response.data.videos.data
            : this.videos.concat(response.data.videos.data);

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
        this.paginationScroll(this, document, 'getVideos');
      }
    },

    created() {
      this.getVideos();
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